<?php
/** Handle a POST request from an ajax call */
function handle(){
    switch ($_POST["action"]) {
        case ACTION_SAVE_POST:
            $data = []; // return data
            
            $post_name = $_POST["name"];
            $post_file = $_POST["file"];
            $post_draft = $_POST["draft"];
            $post_id = $_POST["postid"];
            $post_wasdraft = $_POST["wasdraft"];
            $post_permalink = $_POST["permalink"];
            
            $data["wasdraft"] = $post_wasdraft;
            $data["post_draft"] = $post_draft;
            $data["conditional"] = ($post_wasdraft === "true" && $post_draft === "false");
            
            // dates
            $date = date("Y-m-d");
            
            $post_publishdate = "";
            $post_lastedited = "";
            
            $setPublishDate = ($post_wasdraft === "true" && $post_draft === "false");
            
            if ($setPublishDate) {
                $post_publishdate = date("Y-m-d");
                $post_lastedited = $post_publishdate;
            }
            
            $post_file = mysql_real_escape_string($post_file);
            $post_short_preview = strip_tags(substr($post_file, 0, 30));
            
//            $post_draft = $post_draft ? 1 : 0;
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            if ($post_id == "") { // this is a brand new post (doesn't happen, now posts are saved as new drafts manually)
                $sql = "INSERT INTO blogbase (
                content, 
                posttitle, 
                draft, 
                permalink,
                shortpreview, 
                publishdate, 
                lastedited)
                    VALUES (
                    '" . $post_file . "',
                    \"$post_name\", 
                    $post_draft, 
                    \"$post_permalink\",
                    \"$post_short_preview\",
                    \"$date\",
                    \"$date\")";
            } else {
                // find out if this post is already published
                if ($setPublishDate){ // a draft is being published, set publishdate
                    $sql = "UPDATE blogbase SET content='$post_file'
                    , posttitle='$post_name'
                    , draft='$post_draft'
                    , shortpreview='$post_short_preview'
                    , publishdate='$post_publishdate'
                    , permalink='$post_permalink'
                    , lastedited='$post_lastedited'
                    WHERE id=$post_id";
                    $data["route"] = "post was a draft and is set to become published";
                    
                } else { // a draft or post is being updated, don't change publishdate
                    $sql = "UPDATE blogbase SET content='$post_file'
                    , posttitle='$post_name'
                    , draft='$post_draft'
                    , shortpreview='$post_short_preview'
                    , lastedited='$date'
                    , permalink='$post_permalink'
                    WHERE id=$post_id";
                    $data["route"] = "post was a not a draft and is set to be updated";
                }
                
                
            }
            
            $result = $conn->query($sql);

            if ($result === TRUE) {
                $data["postid"] = $post_id == "" ? $conn->insert_id : $post_id;
                $data["draft"] = $post_draft;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
            exit;
            break;
        case ACTION_POST_PERMALINK:
            $post_id = $_POST["postid"];
            $permalink = $_POST["permalink"];
            
            $sql = "UPDATE blogbase 
                SET permalink=\"$permalink\"
                WHERE id=$post_id";
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["query"] = $sql;
                $data["postid"] = $post_id;
                $data["permalink"] = $permalink;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            
            exit;
            break;
        case ACTION_POST_DRAFT_STATUS:
            $post_id = $_POST["postid"];
            $draft = $_POST["draft"];
            
            $sql = "UPDATE blogbase 
                SET draft=$draft
                WHERE id=$post_id";
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["query"] = $sql;
                $data["postid"] = $post_id;
                $data["draft"] = $draft;
                echo json_encode($data);
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            break;
        case ACTION_SAVE_TAG:
            $tag_name = $_POST["name"];
            $post_id = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "INSERT INTO tagbase (tagname) VALUES (\"$tag_name\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["tag_name"] = $tag_name;
                $data["tag_id"] = $conn->insert_id;
                
                // update the tagblogmap
                $sql = "INSERT INTO tagblogmap (postid, tagid) VALUES ($post_id, $conn->insert_id)";
                $result = $conn->query($sql);
                if ($result === TRUE) {
                    echo json_encode($data);
                } else {
                    // failed
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            exit;
            break;
        case ACTION_ADD_TAG_TO_POST:
            $tagid = $_POST["tagid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "INSERT INTO tagblogmap (tagid, postid)
                VALUES (\"$tagid\", \"$postid\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_REMOVE_TAG_FROM_POST:
            $tagid = $_POST["tagid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "DELETE FROM tagblogmap
                WHERE tagid = $tagid AND postid = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_SAVE_CAT:
            $cat_name = $_POST["name"];
            $post_id = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "INSERT INTO catbase (catname) VALUES (\"$cat_name\")";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data = [];
                $data["cat_name"] = $cat_name;
                $data["cat_id"] = $conn->insert_id;
                
                // update the tagblogmap
                $sql = "UPDATE blogbase SET category = $conn->insert_id WHERE id = $post_id";
                $result = $conn->query($sql);
                if ($result === TRUE) {
                    echo json_encode($data);
                } else {
                    // failed
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            exit;
            break;
        case ACTION_ADD_CAT_TO_POST:
            $catid = $_POST["catid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "UPDATE blogbase SET category = $catid WHERE id = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_REMOVE_CAT_FROM_POST:
            $catid = $_POST["catid"];
            $postid = $_POST["postid"];
                        
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // to do: add binding here
            $sql = "UPDATE blogbase SET category = NULL WHERE id = $postid";
            
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                echo ("Success");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            exit;
            break;
        case ACTION_TAGS_BY_POSTID:
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $post_id = $_POST["postid"];
            
            $sql = "SELECT tagname FROM tagblogview WHERE postid = $post_id";
            
            $result = $conn->query($sql);
            
            if ($result != false) {
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<br>tagname: " . $row["tagname"];
                    }
                } else {
                    echo "Did not find any tags for this post";
                }
            } else {
                echo "Did not find any tags for this post";
            }
            

            $conn->close();
            break;
            exit;
        case ACTION_UPLOAD_IMAGE:
            $dateSuffix = date("Y/m/");
            $imgDir = MEDIA_DIR . $dateSuffix;
            if (!file_exists($imgDir)) {
                mkdir($imgDir, 0777, true);
            }
            
            $data = [];
            
            foreach ($_FILES["imgfile"]["error"] as $key => $error) {
                
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["imgfile"]["tmp_name"][$key];
                    $name = $_FILES["imgfile"]["name"][$key];
                    $original_name = $name;
                    $name = $imgDir . $name;
                    
                    if (file_exists($name)) {
                        $regex = "/\(([0-9])\)/";
                        $nameparts = explode(".", $name);
                        
                        $shortname = "";
                        $extension = "";
                        if (sizeof($nameparts) > 1) {
                            $shortname = $nameparts[0];
                            for ($i = 1; $i < sizeof($nameparts); $i++) {
                                $extension = $extension . "." . $nameparts[$i];
                            }
                        } else {
                            $shortname = $name;
                        }
                        
                        $testname = $shortname . " (1)";
                        if (file_exists($testname . $extension)) {
                            $shortname = $testname;
                            
                            $count = 2;
                            $badname = true;
                            while($badname) {
                                $shortname = substr($shortname, 0, -3);
                                $shortname = $shortname . "($count)";
                                $testname = $shortname . $extension;
                                if (!file_exists($testname)){
                                    $badname = false;
                                } else {
                                    $count++;
                                }
                            }
                            $name = $shortname . $extension;
                            
                        } else { // file name with " (1)" does not exist
                            $name = $testname . $extension;
                        }
                        
                        $parts = explode("/", $name);
                        $imgFileName = $parts[sizeof($parts) - 1];
                        $img_url = MEDIA_URL . $dateSuffix . $imgFileName; 
                        
                        $data["original_name"] = $original_name;
                        $data["saved_as_name"] = $name;
                        $data["success"] = true;
                        $data["img_url"] = $img_url;
                        $data["message"] = "Success!";
                        
                        echo json_encode($data);
                    }
                    move_uploaded_file($tmp_name, "$name");
                } else {
                    $data["success"] = false;
                    $data["message"] = "One or more images did not upload successfully.";
                    echo json_encode($data);
                }
            }
            exit;
            break;
        case ACTION_UPLOAD_FEATURED_IMAGE:
            $data = [];
            $data = uploadImage();
//            if ($data["success"] == "false") {
//                echo json_encode($data);exit;
//            }
            $post_id = $_POST["postid"];
            $image_url = $data["img_url"];
            
            $sql = "UPDATE blogbase 
                SET featuredimage=\"$image_url\"
                WHERE id=$post_id";
            
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $result = $conn->query($sql);
            
            if ($result === TRUE) {
                $data["query"] = $sql;
                $data["postid"] = $post_id;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
            $conn->close();
            
            echo json_encode($data);exit;
            
            exit;
            break;
        
        default:
            exit;
            ;
    }   // end of switch statement
    exit; // last line of handle()
}
/** Functions called manually by model files */
function saveNewDraft() {
    if (!isset($_SESSION["userID"])) {
        exit;
        return;
    }
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $date = date("F j, Y");
    $title = "Draft Created on " . $date;
    $isDraft = true;
    $sql = "INSERT INTO blogbase (content, posttitle, draft)
            VALUES (\"\", \"$title\", $isDraft)";
    
    $result = $conn->query($sql);

    if ($result === TRUE) {
        $post_id = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    return $post_id;
}
function loadTagsByPostId($postid) {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT tagname FROM tagblogview WHERE postid = $postid";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<br>tagname:" . $row["tagname"];
        }
    } else {
        echo "Did not find any tags for this post";
    }

    $conn->close();
}
function updateTagsByPostId() {
    
}
function uploadImage(){
    $dateSuffix = date("Y/m/");
    $imgDir = MEDIA_DIR . $dateSuffix;
    if (!file_exists($imgDir)) {
        mkdir($imgDir, 0777, true);
    }

    $data = [];

    foreach ($_FILES["imgfile"]["error"] as $key => $error) {
        
        if ($error == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["imgfile"]["tmp_name"][$key];
            $name = $_FILES["imgfile"]["name"][$key];
            $original_name = $name;
            $name = $imgDir . $name;

            if (file_exists($name)) {
                $regex = "/\(([0-9])\)/";
                $nameparts = explode(".", $name);

                $shortname = "";
                $extension = "";
                if (sizeof($nameparts) > 1) {
                    $shortname = $nameparts[0];
                    for ($i = 1; $i < sizeof($nameparts); $i++) {
                        $extension = $extension . "." . $nameparts[$i];
                    }
                } else {
                    $shortname = $name;
                }

                $testname = $shortname . " (1)";
                if (file_exists($testname . $extension)) {
                    $shortname = $testname;

                    $count = 2;
                    $badname = true;
                    while($badname) {
                        $shortname = substr($shortname, 0, -3);
                        $shortname = $shortname . "($count)";
                        $testname = $shortname . $extension;
                        if (!file_exists($testname)){
                            $badname = false;
                        } else {
                            $count++;
                        }
                    }
                    $name = $shortname . $extension;

                } else { // file name with " (1)" does not exist
                    $name = $testname . $extension;
                }

                $parts = explode("/", $name);
                $imgFileName = $parts[sizeof($parts) - 1];
                $img_url = MEDIA_URL . $dateSuffix . $imgFileName; 

                $data["original_name"] = $original_name;
                $data["saved_as_name"] = $name;
                $data["success"] = true;
                $data["img_url"] = $img_url;
                $data["message"] = "Success!";
               
//                return $data;
            }
            $parts = explode("/", $name);
            $imgFileName = $parts[sizeof($parts) - 1];
            $img_url = MEDIA_URL . $dateSuffix . $imgFileName; 
            
            $data["original_name"] = $original_name;
            $data["saved_as_name"] = $name;
            $data["success"] = true;
            $data["img_url"] = $img_url;
            $data["message"] = "Success!";
            move_uploaded_file($tmp_name, "$name");
        } else {
            $data["success"] = false;
            $data["message"] = "One or more images did not upload successfully.";
//            return $data;
        }
    }
    return $data;
}
