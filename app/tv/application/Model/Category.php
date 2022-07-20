<?php

namespace Mini\Model;

use Mini\Core\Model;
use Mini\Libs\Helper;

class Category extends Model
{
     public function getPublicationFromCategory($permalink)
    {
      
        $limit = 5;
	    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
	    $start_from = ($page-1) * $limit;
	    $sql = "SELECT publication.*, categories.id, categories.* FROM categories 
        LEFT JOIN  categories_relation ON categories.id = categories_relation.categories_id  
        LEFT JOIN  publication ON categories_relation.publication_id = publication.id  
        WHERE categories.permalink = :permalink  ORDER BY publication.id DESC LIMIT $start_from, $limit" ;
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':permalink' => $permalink,
   
        );
        $query->execute($parameters);
        return $query->fetchAll();

      
    }
  
    public function getTotalPublications($type){

	    $sql = "SELECT COUNT(id) as `counter` FROM publication WHERE type = :type";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':type' => $type
        );
	    $query->execute($parameters);
	    return $query->fetch();

    }
      public function getCats($id)
     {
        $sql = "SELECT  categories.* FROM categories 
        LEFT JOIN  categories_relation ON categories_relation.category_id = categories.id
        WHERE categories_relation.publication_id = :id";
        $query = $this->db->prepare($sql);
        $parameters = array(
            ':id' => $id,
   
        );
       // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();
        $query->execute($parameters);
        return $query->fetchAll();
     }

    

}
