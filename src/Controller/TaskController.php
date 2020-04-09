<?php


namespace App\Controller;


use App\Service\PDO_manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private $pdo;

    public function __construct(PDO_manager $pdo){
        $this->pdo = $pdo;
    }

    /**
     * @Route("/api/taken", name = "show_taken", methods={"GET"})
     */
    public function GetTaken(){
        $rows = $this->pdo->GetData('SELECT * FROM taak');
        return (count($rows) > 0) ? new JsonResponse($rows) : new JsonResponse('No tasks found');
        //Alternatief mag dit ook of zelfs json:('Message' => 'No tasks found')
        //return ($rowcount > 0) ? json($rows) : json('No tasks found');
    }

    /**
     * @Route("/api/taak/{slug}", name = "show_taak", methods={"GET"})
     */
    public function GetTaak($slug){
        $rows = $this->pdo->GetData('SELECT * FROM taak WHERE taa_id = "' .$slug. '"');
        return (count($rows) == 1 ) ? new JsonResponse($rows) : new JsonResponse('No task found');
    }

    /**
     * @Route("/api/taken", name = "create_taken", methods={"POST"})
     */
    public function CreateTaak(){
        //Get inputted data
        $data = json_decode(file_get_contents('php://input'));
        //zou beter zijn $_POST te gebruiken bij post zodat je het kunt gebruiken bij een formulier ipv json_decode()
        //$_POST['taa_datum']
        //$_POST['taa_omschr']

        //secure inputted data against coding and respond if not all is filled in
        if ( isset($data->taa_omschr) or isset($data->taa_datum) ) {
            $taa_omschr = htmlentities($data->taa_omschr);
            $taa_datum = htmlentities($data->taa_datum);
        } else return new JsonResponse('Fill in all the fields');

        $sql = 'INSERT INTO taak SET taa_omschr = "' .$taa_omschr. '", taa_datum = "' .$taa_datum. '"';
        $response = $this->pdo->ExecuteSQL($sql);

        //respond if task is created or not
        return ( $response ) ? new JsonResponse('Task created') : new JsonResponse('Task not created');
    }

    /**
     * @Route("/api/taak/{slug}", name = "edit_taak", methods={"PUT"})
     */
    public function EditTaak($slug){
        //check if task exists in db
        if ( !$this->DoesTaskExist($slug) ) return new JsonResponse('Task with id ' .$slug. ' doesn\'t exist');

        //get inputted data
        $data = json_decode(file_get_contents('php://input'));

        //secure $slug against coding
        $slug = htmlentities($slug);
        $taa_datum = htmlentities( $data->taa_datum );
        $taa_omschr = htmlentities( $data->taa_omschr );

        $rows = $this->pdo->ExecuteSQL(
                "UPDATE taak SET
                        taa_omschr = '". $taa_omschr ."', taa_datum = '". $taa_datum ."'
                        WHERE taa_id =  '".$slug."'");

        //respond if task is edited or not
        return ( $rows ) ? new JsonResponse('Task updated') : new JsonResponse('Task not updated');
    }

    /**
     * @Route("/api/taak/{slug}", name = "delete_taak", methods={"DELETE"})
     */
    public function DeleteTaak($slug){
        //check if task exists in db
        if ( !$this->DoesTaskExist($slug) ) return new JsonResponse('Task with id ' .$slug. ' doesn\'t exist');

        //secure $slug against coding
        $slug = htmlentities($slug);

        $rows = $this->pdo->ExecuteSQL('DELETE FROM taak WHERE taa_id = "' . $slug . '"');

        //respond if task is deleted or not
        return ( $rows ) ? new JsonResponse('Task deleted') : new JsonResponse('Task not deleted');
    }

    public function DoesTaskExist($slug){
        $rows = $this->pdo->GetData('SELECT * FROM taak WHERE taa_id = "' . $slug . '"');
        return (count($rows) !== 1) ? false : true;
    }
}