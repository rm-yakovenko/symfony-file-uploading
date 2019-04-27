<?php


namespace App\Controller;

use App\Form\Type\UploadFileType;
use App\UseCase\FileUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request, FileUpload $fileUpload)
    {
        $form = $this->get('form.factory')
            ->createNamed(null, UploadFileType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $webPath = $fileUpload->uploadFile($form->getData()['file']);
            return $this->redirect($webPath);
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
