<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: /produk.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$total_belanja_keseluruhan = 0;

$product_ids = array_keys($cart);
$ids_string = implode(',', array_map('intval', $product_ids));
$sql = "SELECT id, price FROM products WHERE id IN ($ids_string)";
$result = $conn->query($sql);

$product_prices = [];
while ($row = $result->fetch_assoc()) {
    $product_prices[$row['id']] = $row['price'];
}

$conn->begin_transaction();

try {
    $items_to_insert = [];
    foreach ($cart as $product_id => $quantity) {
        $price = $product_prices[$product_id];
        $total_harga_produk = $price * $quantity;
        $total_belanja_keseluruhan += $total_harga_produk;
        
        $items_to_insert[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price_per_item' => $price
        ];
    }

    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
    $stmt_order->bind_param("id", $user_id, $total_belanja_keseluruhan);
    $stmt_order->execute();
    
    $order_id = $conn->insert_id;
    
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)");
    foreach ($items_to_insert as $item) {
        $stmt_items->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price_per_item']);
        $stmt_items->execute();
    }

    $conn->commit();
    
    unset($_SESSION['cart']);
    
    header("Location: /order_success.php?order_id=" . $order_id);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo "Terjadi kesalahan saat memproses pesanan: " . $e->getMessage();
}
?>