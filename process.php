<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'prisijungimai';
    
    // Set DSN
    $dsn = 'mysql:host='.$host.';dbname='.$dbname;
    
    // Create a PDO instance
    $pdo = new PDO($dsn,$user,$password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // Reciving data
    $name = $_POST['name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $rePass = $_POST['re_pass'];

    // Validating Entries
    if(!empty($name) && !empty($lname)){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            echo 'Email not valid';
        }else{
            if(!empty($phone)){
                $phone=intval($phone);
            }
            if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,16}$/', $password)) {
                echo 'The password does not meet the requirements!';
            }else{
                if($password !== $rePass){
                    echo "Your passwords do not match. Try again";
                }else{
                    $password=md5($password);
                    if(checkRegister($pdo,$name,$lname,$email)){
                        $sql = 'INSERT INTO users (name,lname,email,phone,password) VALUES(:name, :lname, :email, :phone, :password)';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['name'=>$name,'lname'=>$lname,'email'=>$email,'phone'=>$phone,'password'=>$password]);
                    }
                }
            }
        }
    }

    function checkRegister($pdo,$name,$lname,$email){
        $stmt = $pdo->query("SELECT * FROM users");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($name===$row['name'] && $lname==$row['lname'] && $email==$row['email']){
                echo "You have already been registered";
            }else{
                return true;
            };
        }
    }
    