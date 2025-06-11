<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Model\MedewerkerSubjectInterface;
use IzBundle\Entity\Document;
use IzBundle\Form\DocumentType;
use IzBundle\Service\DocumentDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/documenten")
 *
 * @Template
 */
class DocumentenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'document';
    protected $entityClass = Document::class;
    protected $formClass = DocumentType::class;
    protected $addMethod = 'addDocument';
    protected $baseRouteName = 'iz_documenten_';

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
        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        [$parentEntity, $this->parentDao] = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen. Extra data: \n queryString: %s \nUser: %s', $this->entityName, $request->getQueryString(), (string) $request->getUserInfo()));
        }

        // Use a temporary Document for form structure, not for direct persistence of multiple files.
        $temporaryEntityForForm = new $this->entityClass();
        if (null !== $parentEntity) {
            // If parentEntity exists, try to associate the temporary entity for form options or defaults.
            // This part of createEntity logic might be relevant if form type depends on parent.
            $parentClassShortName = (new \ReflectionClass($parentEntity))->getShortName();
            $setParentMethod = 'set'.ucfirst($parentClassShortName);
            if (method_exists($temporaryEntityForForm, $setParentMethod)) {
                $temporaryEntityForForm->{$setParentMethod}($parentEntity);
            }
        }

        $form = $this->getForm($this->formClass, $temporaryEntityForForm, array_merge($this->formOptions, [
            'medewerker' => $this->getMedewerker(),
        ]));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFiles = $form->get('documenten')->getData();
            $baseNameFromForm = $form->get('naam')->getData();

            $selectedMedewerker = $form->get('medewerker')->getData();
            $entitiesCreatedCount = 0;

            if (is_array($uploadedFiles) && count($uploadedFiles) > 0) {
                foreach ($uploadedFiles as $uploadedFile) {
                    $documentEntity = new $this->entityClass();
                    $documentEntity->setFile($uploadedFile);

                    $documentName = $baseNameFromForm ?: $uploadedFile->getClientOriginalName();
                    if ($baseNameFromForm && count($uploadedFiles) > 1) {
                        $documentName = $baseNameFromForm.' - '.$uploadedFile->getClientOriginalName();
                    }
                    $documentEntity->setNaam($documentName);

                    if ($selectedMedewerker instanceof \AppBundle\Entity\Medewerker) {
                        $documentEntity->setMedewerker($selectedMedewerker);
                    } else {
                        $this->logger->error('Poging tot opslaan document zonder geldige medewerker. Dit is niet toegestaan en zou door formuliervalidatie voorkomen moeten worden.', ['document_name' => $documentName, 'selected_medewerker_type' => is_object($selectedMedewerker) ? get_class($selectedMedewerker) : gettype($selectedMedewerker)]);
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: Medewerker is niet (correct) geselecteerd/opgegeven. Document niet opgeslagen.");
                        continue;
                    }

                    if (null !== $parentEntity && $this->addMethod && method_exists($parentEntity, $this->addMethod)) {
                        $parentEntity->{$this->addMethod}($documentEntity);
                    }

                    try {
                        $this->beforeCreate($documentEntity);
                        if (!$parentEntity && $this->allowEmpty) {
                            $this->dao->create($documentEntity);
                        }
                        $this->afterCreate($documentEntity);
                        $entitiesCreatedCount++;
                    } catch (UserException $e) {
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: ".$e->getMessage());
                    } catch (\Exception $e) {
                        $this->logger->error("Error persisting document {$uploadedFile->getClientOriginalName()}: ".$e->getMessage(), ['exception' => $e]);
                        $this->addFlash('danger', "Fout bij opslaan bestand {$uploadedFile->getClientOriginalName()}: Er is een fout opgetreden.");
                    }
                }

                if ($parentEntity && $entitiesCreatedCount > 0) {
                    $this->parentDao->update($parentEntity);
                }

                if ($entitiesCreatedCount > 0) {
                    $this->addFlash('success', ucfirst($this->entityName).($entitiesCreatedCount > 1 ? 'en zijn' : ' is').' toegevoegd.');
                }
            } else {
                $this->addFlash('warning', 'Geen documenten geselecteerd of er is een fout opgetreden bij het uploaden.');
            }

            return $this->afterFormSubmitted($request, $parentEntity ?: $temporaryEntityForForm, $form);
        }

        return array_merge([
            'entity' => $temporaryEntityForForm, // Or $parentEntity if more appropriate for the template
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ], $this->addParams($temporaryEntityForForm, $request));
    }
}
