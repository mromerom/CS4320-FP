<?php
if(isset($_POST['submit'])){
    $target_dir = "Manifest Files/Author/";
    $count = 0;
    foreach($_FILES['file']['name'] as $filename){
        $target_file = $target_dir.basename($filename);
        move_uploaded_file($_FILES['file']['tmp_name'][$count], $target_file);
    }
}
?>
<form action="filetest.php" method="post" enctype="multipart/form-data" enctype="multipart/form-data">
    <input type="file" name="file[]" multiple>
        <input type="file" name="file[]" multiple>
    <input type="submit" name="submit">
</form>