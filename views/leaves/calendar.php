<?php
$content = '
<div class="container-fluid">
    <h1>Lịch nghỉ phép</h1>
    <p>Trang lịch nghỉ phép</p>
    <p>Số lượng đơn nghỉ: ' . count($leaves) . '</p>
</div>
';

$this->view('layouts.main', [
    'title' => $title,
    'content' => $content
]);
?>