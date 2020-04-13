<?php
    namespace App\Controller;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;

    class FormController extends Controller {
        /**
         * @Route("/", name="new_input")
         * @Method({"GET", "POST"})
         */
        public function index(Request $request) {
            $form = $this->createFormBuilder()
                ->add('number', NumberType::class, array(
                    'label' => 'Numer:',
                    'attr' => array(
                        'placeholder' => 'Dodaj test-case',
                        'class' => 'form-control'
                    )
                ))
                ->add('send', SubmitType::class, array(
                    'label' => 'Zaakceptuj',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();
            $form->handleRequest($request);
            $myArray = array(0, 1);
            $n = NULL;
            $rangeError = FALSE;
            if($form->isSubmitted() && $form->isValid()) {
                $number = $form->getData();
   
                if((int)$number["number"] >= 1 && (int)$number["number"] <= 99999) {
                    $n = (int)$number["number"];
                    // conditions for an n that is an odd number
                    if ($n % 2 == 1) {
                        $i = 0;
                        while($i <= ($n - 1) / 2) {
                            $myArray[2 * $i] = $myArray[$i];
                            $myArray[2 * $i + 1] = $myArray[$i] + $myArray[$i + 1];
                            $i++;
                        };
                    // conditions for n that is an even number
                    } else {
                        $i = 0;
                        while($i <= $n / 2) {
                            $myArray[2 * $i] = $myArray[$i];
                            $myArray[2 * $i + 1] = $myArray[$i] + $myArray[$i + 1];
                            $i++;
                        };
                        // removing the last elemtn of the array as the index of its last element would be greater than the value of n by one
                    array_pop($myArray);
                    };
                } else {
                    $rangeError = TRUE;
                }
            };
            $largest = max($myArray);

            return $this->render('index.html.twig', [
                'form' => $form->createView(),
                'n' => $n,
                'largest' => $largest,
                'rangeError' => $rangeError,
                'error' => "Wpisz liczbę z przedziału 1 do 99999"
            ]);

        }
    }