<?php

class User extends Database
{
    public function LoginCheck($username, $password)
    {
        $query = "SELECT * FROM user_account WHERE username = :Username AND password = :Password AND isAdmin=1";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':Username', $username, PDO::PARAM_STR);
            $statement->bindValue(':Password', $password, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                $result = $statement->fetch();
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function ChangeState($id, $state)
    {
        $query = "UPDATE user_account SET isOnline=:state WHERE AccountID=:id";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':state', $state, PDO::PARAM_STR);
            $statement->bindValue(':id', $id, PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetInfoAdminUserByID($id) //check if user exist in database
    {
        $query = "SELECT username, password FROM user_account WHERE AccountID=:id AND isAdmin=1";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            $result = $statement->fetch();
            if ($count > 0) {
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetInfoPlayerUserByID($id) //check if user exist in database
    {
        $query = "SELECT username, password, point FROM user_account WHERE AccountID = :id AND isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':id', $id, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            $result = $statement->fetch();
            if ($count > 0) {
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAllPlayer()
    {
        $query = "SELECT AccountID,username,password,point,isPlaying,isOnline FROM user_account WHERE isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                $result = $statement->fetchAll();
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccount()
    {
        $query = "SELECT * FROM user_account";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccountOnline()
    {
        $query = "SELECT * FROM user_account WHERE isOnline=1";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccountOffline()
    {
        $query = "SELECT * FROM user_account WHERE isOnline=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAccStateForJs()
    {
        $query = "SELECT COUNT(*) AS Amount FROM user_account WHERE isOnline=1 UNION ALL SELECT COUNT(*) AS Amount FROM user_account WHERE isOnline=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                $result = $statement->fetchAll();
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetPlayerStateForJs()
    {
        $query = "SELECT COUNT(*) AS Amount FROM user_account WHERE isPlaying=1 UNION ALL SELECT COUNT(*) AS Amount FROM user_account WHERE isPlaying=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                $result = $statement->fetchAll();
                return $result;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccountPlayer()
    {
        $query = "SELECT * FROM user_account WHERE isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccountPlaying()
    {
        $query = "SELECT * FROM user_account WHERE isAdmin=0 AND isPlaying=1";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function GetAmountAccountNotPlaying()
    {
        $query = "SELECT * FROM user_account WHERE isAdmin=0 AND isPlaying=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return $count;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function UpdateAdminAccount($AccountId, $Username, $Password)
    {
        $query1 = "UPDATE user_account SET username=:Username, password=:Password WHERE AccountID=:id AND isAdmin=1";
        try {
            $statement = $this->connect->prepare($query1);
            $statement->bindValue(':Username', $Username, PDO::PARAM_STR);
            $statement->bindValue(':Password', $Password, PDO::PARAM_STR);
            $statement->bindValue(':id', $AccountId, PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function UpdatePlayerAccount($AccountId, $Username, $Password, $Point)
    {
        $query1 = "UPDATE user_account SET username=:Username, password=:Password, point=:Point WHERE AccountID=:id AND isAdmin=0";
        try {
            $statement = $this->connect->prepare($query1);
            $statement->bindValue(':Username', $Username, PDO::PARAM_STR);
            $statement->bindValue(':Password', $Password, PDO::PARAM_STR);
            $statement->bindValue(':Point', $Point, PDO::PARAM_STR);
            $statement->bindValue(':id', $AccountId, PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function CreatePlayerAccount($AccountId, $Username, $Password, $Point)
    {
        $query1 = "INSERT INTO user_account (AccountID, username, password, point) VALUE (:id,:Username,:Password,:Point)";
        try {
            $statement = $this->connect->prepare($query1);
            $statement->bindValue(':id', $AccountId, PDO::PARAM_STR);
            $statement->bindValue(':Username', $Username, PDO::PARAM_STR);
            $statement->bindValue(':Password', $Password, PDO::PARAM_STR);
            $statement->bindValue(':Point', $Point, PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function CheckValidAdminAccountId($AccountId)
    {
        $query = "SELECT * FROM user_account WHERE AccountID=:id AND isAdmin=1";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':id', $AccountId, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function CheckValidPlayerAccount($AccountId)
    {
        $query = "SELECT * FROM user_account WHERE AccountID=:id AND isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':id', $AccountId, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function CheckValidPlayerUsername($Username)
    {
        $query = "SELECT * FROM user_account WHERE username:Username AND isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':Username', $Username, PDO::PARAM_STR);
            $statement->execute();
            $count = $statement->rowCount();
            if ($count > 0) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

    public function DeletePlater($IdAccount)
    {
        $query = "DELETE FROM user_account WHERE AccountID=:id AND isAdmin=0";
        try {
            $statement = $this->connect->prepare($query);
            $statement->bindValue(':id', $IdAccount, PDO::PARAM_STR);
            if ($statement->execute()) {
                return true;
            } else return false;
        } catch (PDOException $e) {
        }
    }

}

?>
