<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\db\ConnectionFactory;
use PDO;

class DeletePostAction extends Action
{

    public function execute(): string
    {
        $html = "";
        $db = ConnectionFactory::makeConnection();
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $resultset = $db->prepare("SELECT userId FROM POST WHERE postId = ?");
            $resultset->bindParam(1, $id);
            $resultset->execute();
            $resultset = $resultset->fetch(PDO::FETCH_ASSOC);;

            if($resultset['userId'] === $_SESSION['user']->userId){
                $sql = "DELETE FROM HASTAG WHERE postId = ?";
                $resultset = $db->prepare($sql);
                $resultset->bindParam(1, $id);
                $resultset->execute();
                $sql = "DELETE FROM POST WHERE postId = ?";
                $resultset = $db->prepare($sql);
                $resultset->bindParam(1, $id);
                $resultset->execute();
            }
        }
        header("Location: index.php");
        return $html;
    }
}