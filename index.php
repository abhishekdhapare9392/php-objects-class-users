<?php

class Users{
    public $conn;
    public $name;
    public $email;
    public $phone;
    public $message;
    
    // Connect DB

    function connectDB(){
        $servername = "localhost";
        $username = "abhishek";
        $password = "123456";
        $dbname = "php-course";

        // Create connection
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Check connection
    }

    // get all data

    function getAllData(){
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $result = $stmt->fetchAll();
    }

    // insert data

    function set_data($name, $email, $phone, $message){
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    function insertData(){
        $name = $this->name;
        $email = $this->email;
        $phone = $this->phone;
        $message = $this->message;

        $sql = "INSERT INTO users (user_name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
        $stmt = $this->conn->prepare($sql);
        // var_dump($stmt->errorInfo());
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

}


$users = new Users();
$users->connectDB();
$usrs = $users->getAllData();

if(isset($_POST['submit'])){
    $name = $_POST['user_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    if(empty($name) || empty($email) || empty($phone) || empty($message)){
        $_SESSION['error'] = "Please fill all the fields";
    } else {
        $users->set_data($name, $email, $phone, $message);
        if($users->insertData()){
            $_SESSION['success'] =  "Data Inserted successfully";
            $users->getAllData();
        } else {
            $_SESSION['error'] = "Something went wrong";
        }
    }
}

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id = '$id'";
    $stmt = $users->conn->prepare($sql);
    if($stmt->execute()){
        $_SESSION['success'] = "Data Deleted successfully";
        $users->getAllData();
    } else {
        $_SESSION['error'] = "Something went wrong";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users with Class and Objects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="text-center">Users</h1>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usrs as $usr){ ?>
                            <tr>
                                <td><?php echo $usr['user_name']; ?></td>
                                <td><?php echo $usr['email']; ?></td>
                                <td><?php echo $usr['phone']; ?></td>
                                <td><?php echo $usr['message']; ?></td>
                                <td>
                                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $usr['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h1 class="text-center">Add User</h1>
                    <?php 
                        if(isset($_SESSION['success'])){
                            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                            unset($_SESSION['success']);
                        } else if(isset($_SESSION['error'])){
                            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        }
                    ?>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="form-group">
                            <label for="user_name">Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name"
                                placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary my-3" name="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
    </script>
</body>

</html>