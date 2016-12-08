<!DOCTYPE html>

<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }

    if($_SESSION['usertype'] != 'scientist' && $_SESSION['usertype'] != 'admin') {
        header("Location: index.php");
        exit();
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <style>
            fieldset {
                border: 1px solid;
                margin: 0 2px;
                padding: .35em .625em .75em;
            }
        </style>
        <script>
            var uploadControlCounter=0;
            $(document).ready(function () {
                $("#addFileUpload").click(function (e) {
                    e.preventDefault();
                    createFileUpload();
                });

                $("#uploadUI").on("click", "a.removeUpload", function (e) {
                    e.preventDefault();
                    $(this).parent().remove();
                });

                createFileUpload();
                document.getElementById("uploadControlCounter").value = uploadControlCounter;
            });

            function createFileUpload() {
            $("#uploadUI")
                .append(
                    $("<div />")
                        .attr("id", "fileContainer")
                    .append(
                        $("<input />")
                            .attr("type", "file")
                            .attr("name", "file[]")
                    )
                    .append(" ")
                    .append(
                        $("<a />")
                            .attr("href", "#")
                            .attr("class", "removeUpload")
                            .text("Remove")
                    )
                );
            }
        </script>
    </head>
    <body>
        <?php

            if(isset($_POST['submit'])) {

                function test_input($data){
                    $data = trim($data);
                   $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                $datasetURL = test_input($_POST['datasetURL']);

                                $m = new MongoClient();
                $db = $m->collections;
                $collection = $db->manifests;

                //Check if there is an entry in the collection with the same dataset URL
                if($collection->findOne(array("datasetURL" => $datasetURL)) != NULL) {
                    $_SESSION['message'] = 'invaliddatasetURL';
                    header("Location: createManifest.php");
                    exit();
                }

                $creatorComment = test_input($_POST['creatorComment']);
                $author = $_SESSION['fname']." ".$_SESSION['lname'];
                $title = test_input($_POST['title']);
                $abstract = test_input($_POST['abstract']);
                $publication = test_input($_POST['publication']);

                $i = 1;
                $anonymizedData = array();
                $anonymizedDataCnt = 0;
                while($i <= 11){
                    if(isset($_POST['data'.$i])){
                        $anonymizedDataCnt = $anonymizedDataCnt + 1;
                        $anonymizedData["label".$anonymizedDataCnt] = $_POST['data'.$i];
                    }
                    $i++;
                }
                if($anonymizedDataCnt == 0){
                    $_SESSION['message'] = 'invalidanonymized';
                    header("Location: createManifest.php");
                    exit();
                }else{
                    $anonymizedData["numberData"] = $anonymizedDataCnt;
                }

                $privacyConsiderations = test_input($_POST['privacyConsiderations']);
                $provenance = test_input($_POST['narrative']);
                $name = test_input($_POST['name']);
                $email = test_input($_POST['email']);

                //upload files to server
                $oldmask = umask(0);
                if(!mkdir("ManifestFiles/".$_SESSION['username']."/".str_replace(' ', '', $title), 0777, true)){
                    umask($oldmask);
                    $_SESSION['directoryfail'];
                    header("Location: createManifest.php");
                    exit();
                }
                umask($oldmask);
                $target_dir = "ManifestFiles/".$_SESSION['username']."/".str_replace(' ', '', $title)."/";
                $filesArray = array();
                $fileCnt = 0;
                foreach($_FILES['file']['name'] as $filename){
                    $target_file = $target_dir.basename($filename);
                    move_uploaded_file($_FILES['file']['tmp_name'][$fileCnt], $target_file);
                    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
                    $fileHash = md5_file($filename);
                    $file = array();
                    $file['name'] = $filename;
                    $file['format'] = $fileType;
                    $file['size'] = $_FILES['file']['size'][$fileCnt];
                    $file['url'] = $target_file;
                    $file['checksum'] = $fileHash;
                    $fileCnt++;
                    $filesArray['file'.$fileCnt] = $file;
                }
                $filesArray['fileCnt'] = $fileCnt;


                $today = date("M d y \@ h:i:s");

                //Create the entry for the database
                $entry = array(
                    "datasetURL" => $datasetURL,
                    "author" => $author,
                    "username" => $_SESSION['username'],
                    "dateCreated" => $today,
                    "comment" => $creatorComment,
                    "title" => $title,
                    "researchObject" => array(
                        "title" => $title,
                        "abstract" => $abstract,
                        "privacyEthics" => array(
                            "oversight" => $_POST['oversight'],
                            "informedConsent" => $_POST['informedConsent'],
                            "anonymizedData" => $anonymizedData,
                            "privacyConsiderations" => $privacyConsiderations,
                            ),
                    "provenance" => $provenance,
                    "publications" => array(
                        "publication" => $publication
                        ),
                    "creators" => array(
                        "creator" => array(
                            "name" => $name,
                            "role" => $_POST['role'],
                            "type" => $_POST['type'],
                            "contact" => $email
                            )
                        ),
                    "files" => $filesArray
                    )
                );

                //Insert entry into the users collection
                $collection->insert($entry);
                $_SESSION['message'] = 'success';
            }
            include_once("navbar.php");
            switch ($_SESSION["message"])//Checks for message flags
            {
                case '-1'://All Database errors
                    ?>
                    <div class="alert alert-danger">Could not create manifest.</div>
                    <?php
                    break;
                case 'invaliddatasetURL':
                    ?>
                    <div class="alert alert-warning">Dataset URL is already taken.</div>
                    <?php
                    break;
                case 'invalidanonymized':
                    ?>
                    <div class="alert alert-warning">Input for Anonymized Date is required.</div>
                    <?php
                    break;
                case 'success':
                    ?>
                    <div class="alert alert-success">Manifest successfully created.</div>
                    <?php
                    break;
                case 'filefail':
                    echo "<div class='alert alert-warning'>File ".$_SESSION['file']." failed to upload.</div>";
                    break;
                case 'directoryfail':
                        ?>
                        <div class="alert alert-warning">Failed to make directory</div>;
                        <?php
                        break;
                default:
                    break;
            }
            unset($_SESSION["message"]);
        ?>
        <h1>Create New Manifest</h1>
        <h5>Items marked with an * are required.</h5>
        <form action="createManifest.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Manifest Information</legend>
                    * URL of Dataset<br>
                    <input type="url" name="datasetURL" value="<?php $_POST['datasetURL']?>" required><br>
                    Comments about the manifest or the creator of the manifest.<br>
                    <input type="text" name="creatorComment" value="<?php $_POST['creatorComment']?>"><br>
            </fieldset>
            <fieldset>
                <legend>Dataset Information</legend>
                * Title of dataset or one sentence that describes the contents of the dataset.<br>
                <input type="text" name="title" value="<?php $_POST['title']?>" required><br>
                * Abstract<br>
                <textarea name="abstract" required></textarea><br>
                Publications (cite with APA format)<br>
                <input type="text" name="publication"><br>
                * Provence (Input free text or URL to the location of the provence. Type No Assertion if not applicable)<br>
                <textarea name="narrative" required></textarea><br>
                <fieldset>
                    <legend>Privacy and Ethics</legend>
                    Institutional Oversight<br>
                    <select name="oversight" >
                        <option value="No Assertion">No Assertion</option>
                        <option value="IRB">IRB</option>
                        <option value="REB">REB</option>
                        <option value="REC">REC</option>
                        <option value="Not required">Not required</option>
                        <option value="Other">Other</option>
                    </select><br><br>
                    Informed Consent<br>
                    <input type="radio" name="informedConsent" value="Informed Consent Obtained">Informed Consent Obtained<br>
                    <input type="radio" name="informedConsent" value="Participants Notified">Participants Notified<br>
                    <input type="radio" name="informedConsent" value="No Assertion" checked>No Assertion<br><br>
                    * Anonymized Data (Select all that apply. If none apply, select no assertion.)<br>
                    <input type="checkbox" name="data1" value="names anonymized">Names Anonymized
                    <input type="checkbox" name="data2" value="names excluded">Names Excluded
                    <input type="checkbox" name="data3" value="DoB anonymized">Date of Birth Anonymized
                    <input type="checkbox" name="data4" value="date of death anonymized">Date of Death Anonymized
                    <input type="checkbox" name="data5" value="identifying numbers anonymized">Indentifying Numbers Anonymized<br>
                    <input type="checkbox" name="data6" value="race and ethnicity categories anonymized">Race and Ethnicity Categories Anonymized
                    <input type="checkbox" name="data7" value="religious affiliation anonymized">Religious Affiliation Anonymized
                    <input type="checkbox" name="data8" value="health and wellness data anonymized">Health and Wellness Data Anonymized
                    <input type="checkbox" name="data9" value="location and GPS coordinates anonymized">Location and GPS Coordinated Anonymized<br>
                    <input type="checkbox" name="data10" value="Other">Other
                    <input type="checkbox" name="data11" value="No Assertion">No Assertion<br><br>
                    * Any special considerations needed to be taken to maintain the rights and privacy of subjects when using the dataset. Type No Assertion if not applicable.<br>
                    <textarea name="privacyConsiderations" required></textarea>
                </fieldset>
                <fieldset>
                    <legend>Creators</legend>
                    <label class="inputdefault">* Name</label><br>
                    <input type="text" name="name" required><br>
                    <label class="inputdefault">Role</label><br>
                    <input type="radio" name="role" value="Corporate sponsor">Corporate Sponsor<br>
                    <input type="radio" name="role" value="Grant funder">Grant Funder<br>
                    <input type="radio" name="role" value="Primary investigator">Primary Investigator<br>
                    <input type="radio" name="role" value="Other">Other<br>
                    <input type="radio" name="role" value="No Assertion" checked>No Assertion<br><br>
                    <label class="inputdefault">Type</label><br>
                    <input type="radio" name="type" value="Education institutions">Educations Institutions<br>
                    <input type="radio" name="type" value="Government">Government<br>
                    <input type="radio" name="type" value="NGO">NGO<br>
                    <input type="radio" name="type" value="Individual">Individual<br>
                    <input type="radio" name="type" value="Private for profit entity">Private for Profit Entity<br>
                    <input type="radio" name="type" value="No Assertion" checked>No Assertion<br><br>
                    <label class="inputdefault">* Email</label>
                    <input type="email" name="email" required>
                </fieldset>
                <fieldset>
                    <legend>Files</legend>
                    <p id="uploadUI">
                        <a href="#" class="btn btn-info" id="addFileUpload">Upload another file...</a>
                    </p>
                </fieldset>
            </fieldset>
            <input class="btn btn-info" type="submit" name="submit" value="Submit">
        </form>
    </body>
</html>
