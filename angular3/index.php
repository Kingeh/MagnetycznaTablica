<?php 
    /* 
        Structure:  
        fridge: id, name, zindex, content, x, y, width, height
        data: id, name, session, amount
    */

    /* <-------- CONNECTION (Fill in acording to your database)--------> */
    $conn = new mysqli('localhost','','','')
        or die("Nie można się połączyć ze serwerem");

    if (!$conn->set_charset("utf8")) {
        echo("Error - loading character set utf8 \n");
        return;
    }

    /* <---------- CREATE ----------> */

    /*============ info ============*/
    if (isset($_GET['init'], $_GET['name'])) {

        if ($_GET['init']) {

            $name = $_GET['name'];

            if (!($stmt = $conn->prepare("SELECT * FROM info WHERE `name` = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("s", $name);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }    

            $rs = $stmt->get_result();

            if ($rs->num_rows > 0) {

                $return = [];

                while ($row = $rs->fetch_assoc()) {
                    $return[] = ["name" => $row['name'], "session" => $row['session'], "amount" => $row['amount']];
                }

                if (!($st = $conn->prepare("SELECT * FROM fridge WHERE `name` = ?"))) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                    return;
                }

                $st->bind_param("s", $name);

                if (!$st->execute()) {
                    echo "Execute failed: (" . $st->errno . ") " . $st->error;
                    return;
                }

                $rs = $st->get_result();

                if ($rs->num_rows > 0) {
                    while ($row = $rs->fetch_assoc()) {
                        $return[] = ["noteid" => $row['noteid'], "zindex" => $row['zindex'], "content" => $row['content'], "x" => $row['x'], "y" => $row['y'], "width" => $row['width'], "height" => $row['height']];
                    }
                }

                $st->close();

                echo json_encode($return);
                
            } else {
                
                if (!($st = $conn->prepare("INSERT INTO info (`name`) VALUES(?)"))) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                    return;
                }

                $st->bind_param("s", $name); 

                if (!$st->execute()) {
                    echo "Execute failed: (" . $st->errno . ") " . $st->error;
                    return;
                }

                $st->close();
            }

            $stmt->close();
        }

        
    }

    /*============ fridge (note) ============*/
    if (isset($_POST['create'], $_POST['name'], $_POST['noteid'], $_POST['zindex'], $_POST['content'], $_POST['x'], $_POST['y'], $_POST['width'], $_POST['height'], $_POST['session'], $_POST['amount'])) {
        
        if ($_POST['create']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $zindex = $_POST['zindex'];
            $content = $_POST['content'];
            $x = $_POST['x'];
            $y = $_POST['y'];
            $width = $_POST['width'];
            $height = $_POST['height'];

            if (!($stmt = $conn->prepare("INSERT INTO fridge (`name`, noteid, zindex, content, x, y, width, height) VALUES(?, ?, ?, ?, ?, ?, ?, ?)"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("siisiiii", $name, $noteid, $zindex, $content, $x, $y, $width, $height);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();

            /*========== info UPDATE ==========*/

            $session = $_POST['session'];
            $amount = $_POST['amount'];

            if (!($stmt = $conn->prepare("UPDATE info SET session = ?, amount = ? WHERE `name` = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("iis", $session, $amount, $name);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();
        }
        
    }

    /* <---------- UPDATE ----------> */

    /*============ zindex ============*/
    if (isset($_POST['update'], $_POST['name'], $_POST['noteid'], $_POST['zindex'])) {

        if ($_POST['update']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $zindex = $_POST['zindex'];

            if (!($stmt = $conn->prepare("UPDATE fridge SET zindex = ? WHERE `name` = ? AND noteid = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("isi", $zindex, $name, $noteid);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();
        }
    }

    /*============ position ============*/
    if (isset($_POST['update'], $_POST['name'], $_POST['noteid'], $_POST['x'], $_POST['y'])) {

        if ($_POST['update']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $x = $_POST['x'];
            $y = $_POST['y'];

            if (!($stmt = $conn->prepare("UPDATE fridge SET x = ?, y = ? WHERE `name` = ? AND noteid = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("iisi", $x, $y, $name, $noteid);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();
        }
    }

    /*============ size ============*/
    if (isset($_POST['update'], $_POST['name'], $_POST['noteid'], $_POST['width'], $_POST['height'])) {

        if ($_POST['update']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $width = $_POST['width'];
            $height = $_POST['height'];

            if (!($stmt = $conn->prepare("UPDATE fridge SET width = ?, height = ? WHERE `name` = ? AND noteid = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("iisi", $width, $height, $name, $noteid);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();
        }
    }

    /*============ content ============*/
    if (isset($_POST['update'], $_POST['name'], $_POST['noteid'], $_POST['content'])) {

        if ($_POST['update']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $content = $_POST['content'];

            if (!($stmt = $conn->prepare("UPDATE fridge SET content = ? WHERE `name` = ? AND noteid = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("ssi", $content, $name, $noteid);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();
        }
    }

    /* <---------- DELETE ----------> */

    /*============ note ============*/
    if (isset($_POST['delete'], $_POST['name'], $_POST['noteid'], $_POST['amount'])) {

        if ($_POST['delete']) {

            $name = $_POST['name'];
            $noteid = $_POST['noteid'];
            $amount = $_POST['amount'];

            if (!($stmt = $conn->prepare("DELETE FROM fridge WHERE `name` = ? AND noteid = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("si", $name, $noteid);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();

            /*========== info UPDATE ==========*/

            if (!($stmt = $conn->prepare("UPDATE info SET amount = ? WHERE `name` = ?"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                return;
            }

            $stmt->bind_param("is", $amount, $name);

            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return;
            }

            $stmt->close();

        }
    }
  
?>