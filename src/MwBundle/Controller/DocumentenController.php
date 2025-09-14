<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\Klant;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Model\MedewerkerSubjectInterface;
use MwBundle\Entity\Document;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\Form\DocumentType;
use MwBundle\Service\DocumentDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/documenten")
 */
class DocumentenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'mw_documenten_';
    protected $allowEmpty = true;
    protected $disabledActions = ['index', 'edit'];

    /**
     * @var DocumentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(DocumentDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/add")
     * 
     * @Template
     */
    public function addAction(Request $request)
    {
        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);

        if ($parentEntity instanceof Klant) {
            $this->addMethod = null;
        }

        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen. Extra data: \n queryString: %s \nUser: %s', $this->entityName, $request->getQueryString(), (string) $request->getUserInfo()));
        }

        $temporaryEntityForForm = new $this->entityClass();
        if (null !== $parentEntity) {
            $parentClassShortName = (new \ReflectionClass($parentEntity))->getShortName();
            $setParentMethod = 'set'.ucfirst($parentClassShortName);
            if (method_exists($temporaryEntityForForm, $setParentMethod)) {
                $temporaryEntityForForm->{$setParentMethod}($parentEntity);
            }
        }

        $maxFilesFromRequest = $request->query->getInt('max_files');

        $currentFormOptions = array_merge($this->formOptions, [
            'medewerker' => $this->getMedewerker(),
        ]);

        if (null !== $maxFilesFromRequest && $maxFilesFromRequest > 0) {
            $currentFormOptions['max_files'] = $maxFilesFromRequest;
        }

        $form = $this->getForm($this->formClass, $temporaryEntityForForm, $currentFormOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFiles = $form->get('documenten')->getData();

            $selectedMedewerker = $form->get('medewerker')->getData();
            $entitiesCreatedCount = 0;

            if (is_array($uploadedFiles) && count($uploadedFiles) > 0) {
                foreach ($uploadedFiles as $uploadedFile) {
                    if (!$selectedMedewerker instanceof \AppBundle\Entity\Medewerker) {
                        $this->logger->error('Poging tot opslaan document zonder geldige medewerker. Dit is niet toegestaan en zou door formuliervalidatie voorkomen moeten worden.', ['document_name' => $uploadedFile->getClientOriginalName(), 'selected_medewerker_type' => is_object($selectedMedewerker) ? get_class($selectedMedewerker) : gettype($selectedMedewerker)]);
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: Medewerker is niet (correct) geselecteerd/opgegeven. Document niet opgeslagen.");
                        continue;
                    }

                    $documentEntity = new $this->entityClass($parentEntity, $selectedMedewerker);
                    $documentEntity->setFile($uploadedFile);
                    $documentEntity->setNaam($uploadedFile->getClientOriginalName());

                    if (null !== $parentEntity && $this->addMethod && method_exists($parentEntity, $this->addMethod)) {
                        $parentEntity->{$this->addMethod}($documentEntity);
                    }

                    try {
                        $this->beforeCreate($documentEntity);

                        if ((!$parentEntity && $this->allowEmpty) || ($parentEntity instanceof Klant)) {
                            $this->dao->create($documentEntity);
                        }
                        $this->afterCreate($documentEntity);
                        $entitiesCreatedCount++;
                    } catch (UserException $e) {
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: ".$e->getMessage());
                    } catch (\Exception $e) {
                        $this->logger->error("Error persisting document {$uploadedFile->getClientOriginalName()}: ".$e->getMessage(), ['exception' => $e]);
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: ".$e->getMessage());
                    }
                }

                if ($parentEntity && $entitiesCreatedCount > 0) {
                    if (!$parentEntity instanceof Klant) {
                        $this->parentDao->update($parentEntity);
                    }
                }

                if ($entitiesCreatedCount > 0) {
                    $this->addFlash('success', ucfirst($this->entityName).($entitiesCreatedCount > 1 ? 'en zijn' : ' is').' toegevoegd.');
                }
            } else {
                $this->addFlash('warning', 'Geen documenten geselecteerd of er is een fout opgetreden bij het uploaden.');
            }

            return $this->afterFormSubmitted($request, $parentEntity ?: $temporaryEntityForForm, $form);
        }

        $actualMaxFilesForTemplate = $form->getConfig()->getOption('max_files');

        return array_merge([
            'entity' => $temporaryEntityForForm,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
            'max_files' => $actualMaxFilesForTemplate,
        ], $this->addParams($temporaryEntityForForm, $request));

        
    }

    protected function createEntity($parentEntity = null)
    {
        return new Document($parentEntity, $this->getMedewerker());
    }

    protected function persistEntity($entity, $parentEntity)
    {
        $this->dao->create($entity);
    }
}
