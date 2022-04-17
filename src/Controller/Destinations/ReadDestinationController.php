<?php


namespace App\Controller\Destinations;


use App\Entity\Destination;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadDestinationController extends AbstractController
{
    /**
     * @Route("/admin/destination/all", name="allDestinations")
     */
    public function showAllDestination(PersistenceManagerRegistry $em,PaginatorInterface $paginator, Request $req): Response
    {
        $conn = $em->getConnection();
        $type = "0";
        $sql = '
            SELECT * FROM destination p
            WHERE p.id > :type
           
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['type' => $type]);


        $destinations = $resultSet->fetchAllAssociative();

        $destinations = $paginator->paginate(
            $destinations,
            $req->query->getInt('page', 1),
            3
        );
        return $this->render('admin/Destination/showAllDestinations.html.twig', [
            'destinations' =>  $destinations
        ]);
    }

    #[Route('/admin/destination/{id}', name:'oneDestination', methods:['GET'])]
    public function showOneDestination(PersistenceManagerRegistry $em, int $id): Response
    {
        $destination = $em->getRepository(Destination::Class)->find($id);;
        return $this->render('admin/Destination/oneDestination.html.twig', [
            'id' => $id,
            'destination' => $destination
        ]);
    }

}