<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Database.class.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/lib/Share.class.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

use Carbon\Carbon;

class Notes extends Share
{
    public function __construct($id=null)
    {
        parent::__construct($id, 'note');
        $this->db = Database::getConnection();
        if ($id!=null) {
            $this->id = $id;
            $this->refresh();
        }
    }

    public function refresh()
    {
        if ($this->id != null) {
            $query = "SELECT * FROM notes WHERE id=$this->id";
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

    public function getID()
    {
        return $this->id;
    }

    public function getBody()
    {
        if ($this->data and isset($this->data['body'])) {
            return $this->data['body'];
        }
    }

    public function getFolderID()
    {
        if ($this->data and isset($this->data['folder_id'])) {
            return $this->data['folder_id'];
        }
    }

    public function getTitle()
    {
        if ($this->data and isset($this->data['title'])) {
            return $this->data['title'];
        }
    }

    public function createdAt()
    {
        if ($this->data and isset($this->data['created_at'])) {
            $c = new Carbon($this->data['created_at'], date_default_timezone_get());
            return $c->diffForHumans();
        }
    }

    public function updatedAt()
    {
        if ($this->data and isset($this->data['updated_at'])) {
            $c = new Carbon($this->data['updated_at'], date_default_timezone_get());
            return $c->diffForHumans();
        }
    }

    public function setBody($body)
    {
        if (isset($_SESSION['username']) and $this->getOwner() == $_SESSION['username']) {
            if ($this->id) {
                $query = "UPDATE `notes` SET `body` = '$body' WHERE (`id` = '$this->id');";
                $result = mysqli_query($this->db, $query);
                $this->setUpdated();
                $this->refresh();
                return $result;
            } else {
                throw new Exception("Not found");
            }
        } else {
            throw new Exception("Unauthorized");
        }
    }

    public function setTitle($title)
    {
        if (isset($_SESSION['username']) and $this->getOwner() == $_SESSION['username']) {
            if ($this->id) {
                $query = "UPDATE `notes` SET `title` = '$title' WHERE (`id` = '$this->id');";
                $result = mysqli_query($this->db, $query);
                $this->setUpdated();
                $this->refresh();
                return $result;
            } else {
                throw new Exception("Not found");
            }
        } else {
            throw new Exception("Unauthorized");
        }
    }

    private function setUpdated()
    {
        if (isset($_SESSION['username']) and $this->getOwner() == $_SESSION['username']) {
            if ($this->id) {
                $query = "UPDATE `notes` SET `updated_at` = '".date("Y-m-d H:i:s")."' WHERE (`id` = '$this->id');";
                $result = mysqli_query($this->db, $query);
                if ($result) {
                    $this->refresh();
                    return $result;
                } else {
                    throw new Exception("Something is not right");
                }
            } else {
                throw new Exception("Not found");
            }
        } else {
            throw new Exception("Unauthorized");
        }
    }

    public function delete()
    {
        if (isset($_SESSION['username']) and $this->getOwner() == $_SESSION['username']) {
            if ($this->id) {
                $query = "DELETE FROM `notes` WHERE (`id` = '$this->id');";
                $result = mysqli_query($this->db, $query);
                return $result;
            } else {
                throw new Exception("Note not loaded");
            }
        } else {
            throw new Exception("Unauthorized ");
        }
    }

    public function createNew($title, $body, $folder)
    {
        $f = new Folder($folder);
        if ($f->getOwner() == $_SESSION['username']) {
            if (isset($_SESSION['username']) and strlen($title) <= 45) {
                $query = "INSERT INTO `notes` (`title`, `body`, `owner`, `folder_id`) VALUES ('$title', '$body', '$_SESSION[username]', '$folder');";
                if (mysqli_query($this->db, $query)) {
                    $this->id = mysqli_insert_id($this->db);
                    $this->refresh();
                    return $this->id;
                }
            } else {
                throw new Exception("Cannot create note");
            }
        } else {
            throw new Exception("Unauthorized");
        }
    }
}
