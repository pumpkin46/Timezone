<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Intl\Timezones;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HomeController extends AbstractController
{
    public function index(Request $request): Response
    {

        \Locale::setDefault('en');

        $timezones = Timezones::getNames();
        $offset = Timezones::getRawOffset('Europe/London');        

        $form = $this->createFormBuilder([])
        ->add('timezone',TextType::class,array(
            'label' =>  false,
            'attr' => array(
                    'class' => 'form_group',
                    'placeholder' => 'Input Timezone'
                )
            ))
        ->add('date', 
            TextType::class,
            array(
                'label' =>  false,
                'attr' => array(
                    'class' => 'form_group',
                    'placeholder' => 'Input Date'
                )
            )
        )
        ->add('save', SubmitType::class, ['label' => 'submit'])
        ->getForm();

        $result = ['empty' => true];
        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid()) {
                // perform some action...
                $formdata = $form->getData();
                $date = $formdata['date'];
                $month = date("F", strtotime($date));
                $dayOfMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($date)), date('y',strtotime($date)));
                $dayOfFebrary = cal_days_in_month(CAL_GREGORIAN, 2, date('y',strtotime($date)));
                $offset = Timezones::getRawOffset($formdata['timezone']);
                $result = [
                    'empty' => false,
                    'string' => 'dfdf',
                    'timezone' => $formdata['timezone'],
                    'offsetDiff' => $offset/60,
                    'dayOfMonth' => $dayOfMonth,
                    'month' => $month,
                    'dayOfFebrary' => $dayOfFebrary,
                ];
            }
        }

        return $this->render('base.html.twig' , [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
    public function submit():Response
    {   
        // print_r($this->post->input());
        exit();
    }
}
?>