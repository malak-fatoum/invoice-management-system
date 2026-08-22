<?php

require_once "../config/auth_check.php";

$invoice_id = $_SESSION["invoice_id"];

$sql = "SELECT * FROM invoice_items
        WHERE invoice_id=?
        ORDER BY id";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param($stmt,"i",$invoice_id);

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

$no=1;

$total=0;

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row["description"]) ?></td>

<td><?= $row["quantity"] ?></td>

<td><?= $row["unit_price"] ?></td>

<td><?= $row["total"] ?></td>

<td>

<button
class="deleteBtn"
data-id="<?= $row["id"] ?>">

🗑

</button>

</td>

</tr>

<?php

}

?>