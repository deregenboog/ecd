<?php

declare(strict_types=1);

namespace Tests\MwBundle\Controller;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Service\KlantDaoInterface;
use MwBundle\Controller\DocumentenController;
use MwBundle\Entity\Document;
use MwBundle\Entity\Vrijwilliger;
use MwBundle\Service\DocumentDaoInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class DocumentenControllerTest extends TestCase
{
    /** @var DocumentDaoInterface|MockObject */
    protected $documentDao;

    /** @var \ArrayObject|MockObject */
    protected $entities;

    /** @var LoggerInterface|MockObject */
    protected $logger;

    /** @var Medewerker|MockObject */
    protected $medewerker;

    /** @var DocumentenController|MockObject */
    protected $controller;

    protected function setUp(): void
    {
        $this->documentDao = $this->createMock(DocumentDaoInterface::class);
        $this->entities = $this->createMock(\ArrayObject::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->medewerker = $this->createMock(Medewerker::class);

        // Create a mock of the controller, allowing us to mock its protected methods
        $this->controller = $this->getMockBuilder(DocumentenController::class)
            ->setConstructorArgs([$this->documentDao, $this->entities])
            ->onlyMethods([
                'getForm',
                'getMedewerker',
                'addFlash',
                'getParentConfig',
                // 'beforeCreate' and 'afterCreate' are not mocked to allow the original (likely empty void) methods to be called.
                // Mocking them causes an IncompatibleReturnValueException because mocks return null by default, which violates the void return type.
                'afterFormSubmitted',
                'addParams', // Mock this as it's called at the end
            ])
            ->getMock();

        // Set up common mocks for protected methods
        $this->controller->method('getMedewerker')->willReturn($this->medewerker);
        $this->controller->method('addFlash')->willReturnCallback(function ($type, $message) {
            // Simulate flash message addition, useful for debugging if needed
        });

        // Use helper to set protected properties
        $this->setProtectedProperty($this->controller, 'logger', $this->logger);

        $this->controller->method('afterFormSubmitted')->willReturn(null); // Prevent further redirects/responses
        $this->controller->method('addParams')->willReturn([]); // Return empty array for simplicity
    }

    private function setProtectedProperty(object $object, string $propertyName, $value): void
    {
        $reflection = new \ReflectionObject($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    private function createMockForm(array $formData, bool $isValid = true, array $options = [], bool $isSubmitted = true): MockObject
    {
        $form = $this->createMock(Form::class);
        $form->method('handleRequest')->willReturnSelf();
        $form->method('isSubmitted')->willReturn($isSubmitted);
        $form->method('isValid')->willReturn($isValid);

        // Mock form data
        $form->method('get')->willReturnCallback(function ($name) use ($formData, $form) {
            if ($name === 'documenten') {
                $uploadedFiles = [];
                foreach ($formData['documenten'] as $fileData) {
                    $uploadedFiles[] = $this->createMockUploadedFile($fileData['name'], $fileData['size'], $fileData['mimeType']);
                }
                $childForm = $this->createMock(Form::class);
                $childForm->method('getData')->willReturn($uploadedFiles);

                return $childForm;
            } elseif ($name === 'medewerker') {
                $childForm = $this->createMock(Form::class);
                $childForm->method('getData')->willReturn($formData['medewerker']);

                return $childForm;
            }

            return $form;
        });

        // Mock form config for options like 'max_files'
        $formConfig = $this->createMock(FormConfigInterface::class);
        $formConfig->method('getOption')->willReturnCallback(function ($optionName) use ($options) {
            return $options[$optionName] ?? null;
        });
        $form->method('getConfig')->willReturn($formConfig);

        return $form;
    }

    private function createMockUploadedFile(string $name, int $size, string $mimeType): MockObject
    {
        $file = $this->createMock(UploadedFile::class);
        $file->method('getClientOriginalName')->willReturn($name);
        $file->method('getSize')->willReturn($size);
        $file->method('getMimeType')->willReturn($mimeType);

        return $file;
    }

    private function getPrivateProperty(object $object, string $propertyName)
    {
        $reflection = new \ReflectionObject($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    public function testAddActionSuccessfulUploadForKlantParent()
    {
        $klant = $this->createMock(Klant::class);
        $klant->method('getId')->willReturn(1);
        $klantDao = $this->createMock(KlantDaoInterface::class); // Mock for parentDao

        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);
        $this->setProtectedProperty($this->controller, 'addMethod', null); // Set to null for Klant

        $uploadedFileName = 'test_document.pdf';

        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 1024, 'mimeType' => 'application/pdf']],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        // Expect setKlant to be called on the Document entity
        $this->documentDao->expects($this->once())
            ->method('create')
            ->with($this->callback(function (Document $document) use ($klant, $uploadedFileName) {
                $this->assertEquals($klant, $this->getPrivateProperty($document, 'klant'));
                $this->assertEquals($uploadedFileName, $document->getNaam());
                $this->assertEquals($this->medewerker, $document->getMedewerker());

                return true;
            }));

        $this->controller->expects($this->once())
                    ->method('addFlash')
                    ->with('success', 'Document is toegevoegd.');

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionSuccessfulUploadForNonKlantParentWithAddMethod()
    {
        $vrijwilliger = $this->createMock(Vrijwilliger::class);
        $vrijwilliger->method('getId')->willReturn(1);
        $vrijwilliger->expects($this->once())->method('addDocument'); // Expect addDocument to be called

        // The parent DAO must match the parent entity type. For a Vrijwilliger, this is the VrijwilligerDaoInterface.
        $parentDao = $this->createMock(\MwBundle\Service\VrijwilligerDaoInterface::class);
        $parentDao->expects($this->once())->method('update')->with($vrijwilliger);

        $this->controller->method('getParentConfig')->willReturn([$vrijwilliger, $parentDao]);
        $this->setProtectedProperty($this->controller, 'addMethod', 'addDocument'); // Set addMethod for non-Klant parent

        $uploadedFileName = 'another_doc.docx';

        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 2048, 'mimeType' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        // For non-Klant parents with an addMethod, persistence is handled by updating the parent (cascade).
        // The controller does not call documentDao->create() directly in this case.
        $this->documentDao->expects($this->never())->method('create');

        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with('success', 'Document is toegevoegd.');

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionNoFilesUploaded()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);

        $form = $this->createMockForm([
            'documenten' => [], // No files
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->never())->method('create');
        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with('warning', 'Geen documenten geselecteerd of er is een fout opgetreden bij het uploaden.');

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionInvalidMedewerker()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);

        $uploadedFileName = 'invalid_medewerker.txt';

        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 512, 'mimeType' => 'text/plain']],
            'medewerker' => new \stdClass(), // Invalid medewerker
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->never())->method('create');
        $this->logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Poging tot opslaan document zonder geldige medewerker.'), $this->anything());
        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with('danger', $this->stringContains("Fout bij opslaan bestand {$uploadedFileName}: Medewerker is niet (correct) geselecteerd/opgegeven. Document niet opgeslagen."));

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionUserExceptionDuringCreate()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);
        $this->setProtectedProperty($this->controller, 'addMethod', null);

        $uploadedFileName = 'user_exception.pdf';
        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 1024, 'mimeType' => 'application/pdf']],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->once())
            ->method('create')
            ->willThrowException(new UserException('Test User Exception'));

        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with('danger', "Fout bij opslaan bestand {$uploadedFileName}: Test User Exception");

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionGenericExceptionDuringCreate()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);
        $this->setProtectedProperty($this->controller, 'addMethod', null);

        $uploadedFileName = 'generic_exception.pdf';
        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 1024, 'mimeType' => 'application/pdf']],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->once())
            ->method('create')
            ->willThrowException(new \Exception('Generic Test Exception'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains('Error persisting document'), $this->anything());
        $this->controller->expects($this->once())
            ->method('addFlash')
            ->with('danger', "Fout bij opslaan bestand {$uploadedFileName}: Generic Test Exception");

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionHandlesMultipleFilesWithOneFailure()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);
        $this->setProtectedProperty($this->controller, 'addMethod', null);

        $file1 = 'success.pdf';
        $file2 = 'failure.pdf';

        $form = $this->createMockForm([
            'documenten' => [
                ['name' => $file1, 'size' => 1024, 'mimeType' => 'application/pdf'],
                ['name' => $file2, 'size' => 1024, 'mimeType' => 'application/pdf'],
            ],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->exactly(2))
            ->method('create')
            ->withConsecutive(
                [$this->callback(fn (Document $doc) => $doc->getNaam() === $file1)],
                [$this->callback(fn (Document $doc) => $doc->getNaam() === $file2)]
            )
            ->will($this->onConsecutiveCalls(
                $this->returnArgument(0), // first call succeeds
                $this->throwException(new UserException('This one failed')) // second call fails
            ));

        $this->controller->expects($this->exactly(2))
            ->method('addFlash')
            ->withConsecutive(
                ['danger', "Fout bij opslaan bestand {$file2}: This one failed"],
                ['success', 'Document is toegevoegd.']
            );

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionSuccessfulUploadWithNoParent()
    {
        // This tests the flow where documents are not attached to a parent entity,
        // which is allowed when $allowEmpty is true (default).
        $this->controller->method('getParentConfig')->willReturn([null, null]);
        $this->setProtectedProperty($this->controller, 'addMethod', null);

        $uploadedFileName = 'orphan_document.pdf';

        $form = $this->createMockForm([
            'documenten' => [['name' => $uploadedFileName, 'size' => 1024, 'mimeType' => 'application/pdf']],
            'medewerker' => $this->medewerker,
        ]);
        $this->controller->method('getForm')->willReturn($form);

        $this->documentDao->expects($this->once())
            ->method('create')
            ->with($this->callback(function (Document $document) use ($uploadedFileName) {
                $this->assertNull($this->getPrivateProperty($document, 'klant'));
                $this->assertEquals($uploadedFileName, $document->getNaam());
                $this->assertEquals($this->medewerker, $document->getMedewerker());

                return true;
            }));

        $this->controller->expects($this->once())->method('addFlash')->with('success', 'Document is toegevoegd.');

        $request = Request::create('/documenten/add');
        $this->controller->addAction($request);
    }

    public function testAddActionHandlesMaxFilesParameter()
    {
        $klant = $this->createMock(Klant::class);
        $klantDao = $this->createMock(KlantDaoInterface::class);
        $this->controller->method('getParentConfig')->willReturn([$klant, $klantDao]);

        $maxFiles = 5;
        $form = $this->createMockForm(
            ['documenten' => [], 'medewerker' => $this->medewerker],
            true,
            ['max_files' => $maxFiles], // Simulate form option being set
            false // Form is not submitted for this test
        );

        // Expect getForm to be called with the correct options, including max_files from the request
        $this->controller->expects($this->once())
            ->method('getForm')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback(function ($options) use ($maxFiles) {
                    $this->assertArrayHasKey('max_files', $options);
                    $this->assertEquals($maxFiles, $options['max_files']);

                    return true;
                })
            )
            ->willReturn($form);

        $request = Request::create('/documenten/add', 'GET', ['max_files' => $maxFiles]);
        $result = $this->controller->addAction($request);

        // The result should be an array of parameters for the template
        $this->assertIsArray($result);
        $this->assertArrayHasKey('max_files', $result);
        $this->assertEquals($maxFiles, $result['max_files']);
    }

    public function testAddActionThrowsRuntimeExceptionWhenAddMethodIsNullAndAllowEmptyIsFalse()
    {
        $this->setProtectedProperty($this->controller, 'addMethod', null);
        $this->setProtectedProperty($this->controller, 'allowEmpty', false); // Default is true, so explicitly set to false

        $this->controller->method('getParentConfig')->willReturn([$this->createMock(Vrijwilliger::class), $this->createMock(DocumentDaoInterface::class)]); // Non-Klant parent

        $form = $this->createMockForm(['documenten' => [], 'medewerker' => $this->medewerker]);
        $this->controller->method('getForm')->willReturn($form);

        $request = Request::create('/documenten/add');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Property $addMethod must be set in class '.get_class($this->controller));

        $this->controller->addAction($request);
    }

    public function testAddActionThrowsAppExceptionWhenParentEntityIsNullAndAllowEmptyIsFalse()
    {
        $this->setProtectedProperty($this->controller, 'allowEmpty', false); // Default is true, so explicitly set to false
        $this->controller->method('getParentConfig')->willReturn([null, null]); // No parent entity

        $form = $this->createMockForm(['documenten' => [], 'medewerker' => $this->medewerker]);
        $this->controller->method('getForm')->willReturn($form);

        $request = Request::create('/documenten/add');

        $this->expectException(AppException::class);
        $this->expectExceptionMessageMatches('/^Kan geen document aan deze entiteit toevoegen./');

        $this->controller->addAction($request);
    }

    public function testCreateEntity()
    {
        $reflectionMethod = new \ReflectionMethod(DocumentenController::class, 'createEntity');
        $reflectionMethod->setAccessible(true);

        $klant = $this->createMock(Klant::class);
        $document = $reflectionMethod->invoke($this->controller, $klant);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertEquals($klant, $this->getPrivateProperty($document, 'klant'));
        $this->assertEquals($this->medewerker, $document->getMedewerker());
    }

    public function testPersistEntity()
    {
        $reflectionMethod = new \ReflectionMethod(DocumentenController::class, 'persistEntity');
        $reflectionMethod->setAccessible(true);

        $document = new Document();
        $klant = $this->createMock(Klant::class);

        $this->documentDao->expects($this->once())->method('create')->with($document);

        $reflectionMethod->invoke($this->controller, $document, $klant);
    }
}