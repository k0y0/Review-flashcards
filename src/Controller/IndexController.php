<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Test;
use App\Service\SessionService;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(SessionService $s, ManagerRegistry $doctrine): Response
    {
        $testId = $s->getTestId();
        $em = $doctrine->getManager();

        /** @var Test $test */
        $test = $em->getRepository(Test::class)->findOneBy(["uid" => $testId]);

        if(empty($test)){
            $countries = $em->getRepository(Country::class)->getRandomCountries(4);
            $json = array();
            /** @var Country $country */
            $question = null;
            foreach ($countries as $country){
                $arr = array(
                    "id" => $country->getId(),
                    "type" => rand(0,1),
                    "guessed" => null,
                    "visited" => false,
                );
                $json[$country->getId()] = $arr;
                if(is_null($question)){
                    $question = $arr;
                    $question['country'] = $country;
                }
            }
            $test = (new Test())
                ->setBeginTimestamp(new \DateTime())
                ->setUid($testId)
                ->setQuestions($json);

            $em->persist($test);
            $em->flush();

            $counter = array("current" => 1, "all" => sizeof($countries));

        }else{
            $question = null;
            $counter = array("current" => 1 , "all" => 0, "correct" => 0);
            foreach ($test->getQuestions() as $id => $q){
                if(is_null($q["guessed"]) && empty($question)){
                    $question = $q;
                    $question['country'] = $em->getRepository(Country::class)->find($id);
                }
                if($q["visited"]){
                    $counter["current"]++;
                    if($q["guessed"])
                        $counter["correct"]++;
                }

                $counter["all"]++;
            }
        }

        return $this->render('index/index.html.twig', [
            "counter" => $counter,
            'testId' => $testId,
            'question' => $question,
            'test'  => $test
        ]);
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @return Response
     * @Route("/submitAnswer", name="app_submit_answer")
     */
    public function submitAnswer(Request $request, ManagerRegistry $managerRegistry): Response
    {
        //todo add flash messages with error
        $uid = $request->get("uId");
        $qid = $request->get("qId");
        $answer = $request->get("answer");
        if(empty($uid) || empty($qid) || is_null($answer)|| $request->getMethod() != "POST"){
            //missing required param uid
            return $this->redirectToRoute("app_index");
        }

        $em = $managerRegistry->getManager();
        /** @var Test $test */
        $test = $em->getRepository(Test::class)->findOneBy(["uid" => $uid]);

        if(empty($test)){
            //test not found
            return $this->redirectToRoute("app_index");
        }
        if($test->isFinished()){
            //test is finished you should not be able to edit answer
            return $this->redirectToRoute("app_index");
        }

        $questions = $test->getQuestions();

        if(empty($questions[$qid])) {
            //wrong question id
            return $this->redirectToRoute("app_index");
        }

        if(!is_null($questions[$qid]["guessed"])){
            //question guessed, do not try to change your previous guess
            return $this->redirectToRoute("app_index");
        }

        $questions[$qid]["guessed"] = ($answer == "true");
        $questions[$qid]["visited"] = true;


        if($answer == "true"){
            $test->setCorrect($test->getCorrect()+1);
        }
        //check if all answers was guessed;
        $finished = true;
        foreach ($questions as $question){
            if(is_null($question['guessed'])){
                $finished = false;
                break;
            }
        }

        if($finished){
            $test->setFinished(true);
            $test->setFinishTimestamp(new \DateTime());
        }

        $test->setQuestions($questions);
        $em->persist($test);
        $em->flush();

        return $this->redirectToRoute("app_index");
    }

    /**
     * @return Response
     * @Route("/beginNew", name="app_begin_new_test")
     */
    public function beginNewTest(SessionService $sessionService): Response
    {
        $sessionService->setNewId();
        return $this->redirectToRoute("app_index");
    }

    /**
     * @return Response
     * @Route("/history", name="app_test_history")
     */
    public function testHistory(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $from = $request->get("from");
        $to = $request->get("to");
        $dtFrom = \DateTime::createFromFormat("Y-m-d\TH:i", $from);
        $dtTo = \DateTime::createFromFormat("Y-m-d\TH:i", $to);

        $em = $managerRegistry->getManager();

        $tests = $em->getRepository(Test::class)->findAllFilter($dtFrom, $dtTo);

        return $this->render('index/history.html.twig', [
            "tests" => $tests
        ]);
    }
}
