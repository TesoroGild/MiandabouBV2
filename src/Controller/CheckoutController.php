<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Orders;
use App\Entity\Items;
use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Repository\OrdersRepository;
use App\Repository\UsersRepository;

final class CheckoutController extends AbstractController
{
    #[Route('/api/checkout', name: 'app_checkout', methods: ['POST'])]
    public function setuporder(Request $request, LoggerInterface $logger,
        EntityManagerInterface $entityManager, UsersRepository $usersRepository,
        AddressRepository $addressRepository, OrdersRepository $ordersRepository): JsonResponse
    {
        //TODO
        //change name of method
        try {
            //TEST ZONE
            $jsonData = json_decode($request->getContent(), true);
            
            // ORDER
            $order = new Orders();
            $userEmail = $jsonData['order']['userEmail'];
            $order->setClient($usersRepository->findUser($userEmail));
            $total = $jsonData['order']['total'];
            $order->setTotal($total);
            $timestamp = new \DateTime();
            $order->setTimecreated($timestamp);
            $expectedDateShipping = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
            $expectedDateShipping->modify('+1 week');
            $timestring = $timestamp->format('Y-m-d');
            //todo
            $orderCode = $userEmail . $total . $timestring;
            $order->setOrdercode($orderCode);
            $itemsOrdered = $jsonData['items'];
            foreach ($itemsOrdered as $item) {
                $order->addItem($item);
            }

            // ADDRESS
            $address = new Address();
            $fulladdress = $jsonData['address']['fulladdress'];
            $city = $jsonData['address']['city'];
            $zipcode = $jsonData['address']['zipcode'];
            $state = $jsonData['address']['state'];
            $country = $jsonData['address']['country'];
            $appnumber = $jsonData['address']['appnumber'];
            $address->setFulladdress($fulladdress);
            $address->setCity($city);
            $address->setZipcode($zipcode);
            $address->setState($state);
            $address->setCountry($country);
            $address->setAppnumber($appnumber);
            //users

            $entityManager->persist($address);

            // ORDER
            $existingAddress = $addressRepository->findAddress($fulladdress, $city, $zipcode, $state, $country, $appnumber);

            if ($existingAddress != null) {
                $order->addAddress($existingAddress);
                $meansofcommunication = $jsonData['additionalInfos']['meansofcommunication'];
                if ($meansofcommunication == "emailcommunication") $order->setCommunicationchannel($userEmail);
                else if ($meansofcommunication == "phonecommunication") $order->setCommunicationchannel($jsonData['additionalInfos']['phonenumber']);
                else $order->setCommunicationchannel('Aucune communication');

                $entityManager->persist($order);
                $entityManager->flush();

                $orderRes = $ordersRepository->findOrder($orderCode);

                if ($orderRes != null) {
                    return $this->json([
                        'order' => $order,
                        'items' => $itemsOrdered,
                        'address' => $existingAddress,
                        'msg' => 'Commande passée avec succès!'
                    ], Response::HTTP_OK);
                } else {
                    //**oublie pas de supprimer les donnees
                    return $this->json([
                        'order' => $order,
                        'items' => $itemsOrdered,
                        'address' => $existingAddress,
                        'msg' => 'ERREUR ORdER'
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                //**oublie pas de supprimer les donnees
                return $this->json([
                    'order' => $order,
                    'items' => $itemsOrdered,
                    'address' => $existingAddress,
                'msg' => 'ERREUR ADDRESS'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $logger->error('Erreur : ' . $e->getMessage());
            return $this->json(
                ['msg' => "Erreur inattendue."],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
