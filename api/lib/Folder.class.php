<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Share.class.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Carbon\Carbon;

class Folder extends Share
{
    private $db;
    private $data = null;
    private $id = null;

    public function __construct($id = null)
    {
        parent::__construct($id, 'folder');
        $this->db = Database::getConnection();
        if ($id!=null) {
            $this->id = $id;
            $this->refresh();
        }
    }

    public function getName()
    {
        if ($this->data and isset($this->data['name'])) {
            return $this->data['name'];
        }
    }

    public function getId()
    {
        if ($this->id) {
            return $this->id;
        }
    }

    public function createdAt()
    {
        if ($this->data and isset($this->data['created_at'])) {
            $c = new Carbon($this->data['created_at'], date_default_timezone_get());
            return $c->diffForHumans();
        }
    }

    public function createNew($name='Default Folder')
    {
        if (isset($_SESSION['username']) and strlen($name) >= 5 and strlen($name) <=45) {
            $query = "INSERT INTO `folders` (`name`, `owner`) VALUES ('$name', '$_SESSION[username]');";
            if (mysqli_query($this->db, $query)) {
                $this->id = mysqli_insert_id($this->db);
                return $this->id;
            }
        } else {
            throw new Exception("Cannot create default folderse");
        }
    }

    public function refresh()
    {
        if ($this->id != null) {
            $query = "SELECT * FROM folders WHERE id=$this->id";
            $result = mysqli_query($this->db, $query);
            if ($result && mysqli_num_rows($result) == 1) {
                $this->data = mysqli_fetch_assoc($result);
                if ($this->getOwner() != $_SESSION['username']) {
                    throw new Exception("Unauthorized");
                }
                $this->id = $this->data['id'];
            } else {
                throw new Exception("Not found");
            }
        }
    }

    public function getOwner()
    {
        if ($this->data and isset($this->data['owner'])) {
            return $this->data['owner'];
        }
    }

    public function rename($name)
    {
        if ($this->id) {
            $query = "UPDATE `folders` SET `name` = '$name' WHERE (`id` = '$this->id');";
            $result = mysqli_query($this->db, $query);
            $this->refresh();
            return $result;
        } else {
            throw new Exception("Not found");
        }
    }

    public function getAllNotes()
    {
        $query = "SELECT * FROM notes WHERE folder_id=$this->id";
        $result = mysqli_query($this->db, $query);
        if ($result) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            for ($i=0; $i<count($data); $i++) {
                $c_at = $data[$i]['created_at'];
                $u_at = $data[$i]['updated_at'];
                $c_c = new Carbon($c_at);
                $u_c = new Carbon($u_at);
                $data[$i]['created'] = $c_c->diffForHumans();
                $data[$i]['updated'] = $u_c->diffForHumans();
            }
            return $data;
        } else {
            return [];
        }
    }

    public function countNotes()
    {
        $query = "SELECT COUNT(*) FROM notes WHERE folder_id=$this->id";
        $result = mysqli_query($this->db, $query);
        if ($result) {
            $data = mysqli_fetch_assoc($result);
            return $data['COUNT(*)'];
        }
    }

    public function delete()
    {
        if (isset($_SESSION['username']) and $this->getOwner() == $_SESSION['username']) {
            $notes = $this->getAllNotes();
            foreach ($notes as $note) {
                $n = new Notes($note['id']);
                $n->delete();
            }

            if ($this->id) {
                $query = "DELETE FROM `folders` WHERE (`id` = '$this->id');";
                $result = mysqli_query($this->db, $query);
                return $result;
            } else {
                throw new Exception("Not found");
            }
        } else {
            throw new Exception("Unauthorized");
        }
    }

    public static function getAllFolders()
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM folders WHERE owner='$_SESSION[username]'";
        $result = mysqli_query($db, $query);
        if ($result) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            for ($i=0; $i<count($data); $i++) {
                $date = $data[$i]['created_at'];
                $c = new Carbon($date);
                $data[$i]['created'] = $c->diffForHumans();
                $f = new Folder($data[$i]['id']);
                $data[$i]['count'] = $f->countNotes();
            }
            return $data;
        } else {
            return [];
        }
    }
}
