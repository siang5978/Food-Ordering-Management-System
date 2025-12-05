<?php
//Observer Design Pattern
require_once __DIR__ . '/../../ObserverPattern/RegisterNotifier.php';
require_once __DIR__ . '/../../ObserverPattern/UpdateProfileNotifier.php';
require_once __DIR__ . '/../../ObserverPattern/ChangePasswordNotifier.php';
require_once __DIR__ . '/../../ObserverPattern/SecurityNotifier.php';

class AccountApiController {
    public static function getAll() {
        header('Content-Type: application/json');

        try {
            $users = Users::all();
            $data = array_map(fn($u) => [
                "id" => $u->getUserID(),
                "username" => $u->getUserName(),
                "password" => $u->getPassword(),
                "email" => $u->getEmail(),
                "phone" => $u->getPhone(),
                "role" => $u->getRole()
            ], $users);

            echo json_encode($data);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Server error"]);
        }
        exit;
    }
    
    public static function getById($params){
        header('Content-Type: application/json');
        try {
            $user = Users::find($params['id']);
            if (!$user) {
                http_response_code(404);
                echo json_encode(["error" => "Account not found"]);
                exit;
            }
            echo json_encode([
                "id" => $user->getUserID(),
                "username" => $user->getUserName(),
                "password" => $user->getPassword(),
                "email" => $user->getEmail(),
                "phone" => $user->getPhone(),
                "role" => $user->getRole()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit;
    }
    
    public static function update($params) {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents("php://input"), true);

        $user = Users::find($params['id']);
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "Account not found"]);
            exit;
        }

        $errors = [];

        if (isset($input['username']) && strlen($input['username']) < 3)
        {$errors[] = "Username must be at least 3 characters.";}
        if (isset($input['password']) && strlen($input['password']) < 8)
        {$errors[] = "Password must be at least 8 characters.";}
        if (isset($input['email']) && !filter_var($input['email'], FILTER_VALIDATE_EMAIL))
        {$errors[] = "Invalid email format.";}
        if (isset($input['phone']) && !preg_match("/^[\d+]+$/", $input['phone']))
        {$errors[] = "Phone number can only contain digits and '+' sign.";}

        if (isset($input['username']) || isset($input['email']) || isset($input['phone'])) {
            $result = $user->verifyEdit(
                    $input['username'] ?? $user->getUserName(),
                    $input['email'] ?? $user->getEmail(),
                    $input['phone'] ?? $user->getPhone()
            );
            if ($result !== null) {
                if ($result & 1)
                {$errors[] = "Username already exists.";}
                if ($result & 2)
                {$errors[] = "Email already exists.";}
                if ($result & 4)
                {$errors[] = "Phone Number already exists.";}
            }
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(["errors" => $errors]);
            exit;
        }

        if (isset($input['username']))
        {$user->setUserName($input['username']);}
        if (isset($input['password']))
        {$user->setPassword($input['password']);}
        if (isset($input['email']))
        {$user->setEmail($input['email']);}
        if (isset($input['phone']))
        {$user->setPhone($input['phone']);}
        if (isset($input['role']))
        {$user->setRole($input['role']);}

        $user->update();
        
        $updateProfileNotifier = new UpdateProfileNotifier();
        $user->add($updateProfileNotifier);
        $user->notify();

        echo json_encode([
            "status" => "updated",
            "user" => [
                "id" => $user->getUserID(),
                "username" => $user->getUserName(),
                "email" => $user->getEmail(),
                "phone" => $user->getPhone(),
                "role" => $user->getRole()
            ]
        ]);
        exit;
    }
    
    public static function delete($params){
        header('Content-Type: application/json');
        $user = Users::find($params['id']);
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "Account not found"]);
            exit;
        }
        $orders = Orders::findByUser($user->getUserID());
        foreach($orders as $order){
            $order->delete();
        }
        
        $user->delete();
        echo json_encode(["status" => "deleted"]);
        exit;
    }
    
    public static function loyaltyReport() {
        header('Content-Type: application/json');

        try {
            $users = Users::all();
            $report = [];

            //Consume Order Api
            $historyJson = file_get_contents("http://localhost/Assignment/index.php/api/orders");
            $allOrders = $historyJson ? json_decode($historyJson, true) : [];

            foreach ($users as $user) {
                $userId = $user->getUserID();

                $userOrders = array_filter($allOrders, function ($order) use ($userId) {
                    return isset($order['order']['userId']) && $order['order']['userId'] == $userId;
                });

                $totalSpent = 0;
                $ordersCount = count($userOrders);

                foreach ($userOrders as $order) {
                    $totalSpent += $order['order']['totalAmount'] ?? 0;
                }

                if ($totalSpent >= 500) {
                    $loyaltyLevel = "Gold";
                } elseif ($totalSpent >= 200) {
                    $loyaltyLevel = "Silver";
                } else {
                    $loyaltyLevel = "Bronze";
                }

                $rewardPoints = intval($totalSpent);

                $report[] = [
                    "userId" => $userId,
                    "username" => $user->getUserName(),
                    "totalSpent" => $totalSpent,
                    "ordersCount" => $ordersCount,
                    "loyaltyLevel" => $loyaltyLevel,
                    "rewardPoints" => $rewardPoints
                ];
            }

            echo json_encode($report);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Server error: " . $e->getMessage()]);
        }

        exit;
    }
    
    public static function loyaltyByID($params) {
        header('Content-Type: application/json');

        try {
            $user = Users::find($params['id']);
            if (!$user) {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
                exit;
            }

            $historyJson = file_get_contents("http://localhost/Assignment/index.php/api/orders");
            $allOrders = $historyJson ? json_decode($historyJson, true) : [];

            $userId = $user->getUserID();

            $userOrders = array_filter($allOrders, function ($order) use ($userId) {
                return isset($order['order']['userId']) && $order['order']['userId'] == $userId;
            });

            $totalSpent = array_sum(array_map(fn($order) => $order['order']['totalAmount'] ?? 0, $userOrders));

            if ($totalSpent >= 500) {
                $loyaltyLevel = "Gold";
            } elseif ($totalSpent >= 200) {
                $loyaltyLevel = "Silver";
            } else {
                $loyaltyLevel = "Bronze";
            }

            echo json_encode(["loyaltyLevel" => $loyaltyLevel]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Server error: " . $e->getMessage()]);
        }

        exit;
    }
}
