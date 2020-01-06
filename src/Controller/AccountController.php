<?php

namespace App\Controller;

use DateTime;
use App\Entity\Account;
use App\Form\AccountType;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Service\TransactionService;
use App\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account_index", methods={"GET"})
     */
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="account_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="account_show", methods={"GET"})
     */
    public function show(Account $account): Response
    {
        $debitsCredits = array_merge($account->getCreditTransaction()->toArray(), $account->getDebitTransaction()->toArray());
        return $this->render('account/show.html.twig', [
            'account' => $account,
            'debitsCredits' => $debitsCredits,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="account_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Account $account): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="account_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Account $account): Response
    {
        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('account_index');
    }


     /**
     * @Route("/addTransaction/{id}", name="new_transaction", methods={"GET","POST"})
     */
    public function addTransaction(Request $request,Account $account,TransactionService $ts): Response
    {
        $transaction = new Transaction();
        $transaction->setDebitAccount($account);

        $form = $this->createForm(TransactionType::class, $transaction, [
            'account' => $account
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datetime = new DateTime();
            $transaction->setDate($datetime);

            $ts->creditDebitAccounts($transaction->getCreditAccount(), $transaction->getDebitAccount(), $transaction->getAmount());
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();


            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        return $this->render('person/creerTransaction.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
      
    }

}
