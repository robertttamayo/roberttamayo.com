<?php

require_once(SRC_DIR . 'helpers/userProfile.php');
require_once(SRC_DIR . 'helpers/post.php');

class BobBlog {
    
    private $headScripts = array();
    private $headStyles = array();
    private $title = "Title";
    private $db_con;
    private $userProfile;
    private $post = array();
    private $activePost;
    private $tags = array();
    private $all_tags = array();
    private $posts = array();

    public function __construct(){
        //$this->db_con = new PDO("mysql:host={DB_SERVER};dbname={DB_NAME}", BD_USERNAME, DB_PASSWORD);
        //$this->getTags(null);
    }
    
    public function headScripts(){
        foreach($this->headScripts as $script) {
            foreach($script as $key => $value){
                switch($key) {
                    case "src":
                        echo "<script src=\"{$script["src"]}\"></script>";
                        break;
                    case "script":
                        echo "<script>" . $script["script"] . "</script>";
                        break;
                    default:
                }
            }
            echo "
    ";
        }
    }
    public function headStyles(){
        foreach($this->headStyles as $style) {
            echo "<link rel=\"stylesheet\" href=\"{$style["href"]}\"/>";
        }
    }
    public function addHeadScript($script){
        $this->headScripts[] = $script;
    }
    public function addHeadStyle($style){
        $this->headStyles[] = $style;
    }
    public function title(){
        echo $this->title;
    }
    public function initUser() {
        $params = array();
        
        $params["name"] = $_SESSION["userID"];
        $params["role"] = $_SESSION["userRole"];
        $params["type"] = $_SESSION["userType"];
        $params["email"] = $_SESSION["userEmail"];
        
        $this->userProfile = new UserProfile($params);
    }
    public function getUserProfile(){
        return $this->userProfile;
    }
    public function getPostId() {
        return null;
    }
    public function getPost($postid){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM blogbase WHERE id = $postid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->activePost = new Post();
                
                $this->activePost->id = $row["id"];
                $this->activePost->content = $row["content"];
                $this->activePost->category = $row["category"];
                $this->activePost->author = $row["author"];
                $this->activePost->publishdate = $row["publishdate"];
                $this->activePost->draft = $row["draft"];
                $this->activePost->lastedited = $row["lastedited"];
                $this->activePost->title = $row["posttitle"];
                $this->activePost->permalink = $row["permalink"];
                $this->activePost->featuredimage = $row["featuredimage"];
                
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        return $this->activePost;
    }
    public function getCatByPermalink($permalink){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM catbase WHERE permalink = \"$permalink\"";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $catid = $row["catid"];
            }
        } else {
            echo "0 results for: ";
            echo $permalink;
        }
        
        $conn->close();
        return $catid;
    }
    public function getPostFromPermalink($permalink){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM blogbase WHERE permalink = \"$permalink\"";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->activePost = new Post();
                
                $this->activePost->id = $row["id"];
                $this->activePost->content = $row["content"];
                $this->activePost->category = $row["category"];
                $this->activePost->author = $row["author"];
                $this->activePost->publishdate = $row["publishdate"];
                $this->activePost->draft = $row["draft"];
                $this->activePost->lastedited = $row["lastedited"];
                $this->activePost->title = $row["posttitle"];
                $this->activePost->permalink = $row["permalink"];
                $this->activePost->featuredimage = $row["featuredimage"];
                
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        return $this->activePost;
    }
    public function getPosts(){
        return $this->posts;
    }
    public function getAllTags(){
        $this->all_tags = null;
        $this->all_tags = array();
        
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM tagbase";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->all_tags[] = ["name" => $row["tagname"], "id" => $row["tagid"], "active" => false];
            }
        } else {
            // 0 results
        }
        $conn->close();
        return $this->all_tags;
    }
    public function getFeaturedImage($postid) {
        if ($postid == null) {
            echo "postid is null"; 
        }
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT featuredimage FROM blogbase WHERE id = $postid";
        $result = $conn->query($sql);
        
        $featured_image = '';
        
        if ($result != false) {
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $featured_image = $row["featuredimage"];
                }
            } else {
                // 0 results
            }
        } else {
            // some kind of error
        }
        return $featured_image;
    }
    public function getTags($postid){
        
        if ($postid == null) {
            echo "postid is null";
        }
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT tagid FROM tagblogview WHERE postid = $postid";
        $result = $conn->query($sql);
        
        if ($result != false) {
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $this->tags[] = ["id" => $row["tagid"]];
                }
            } else {
                // 0 results
            }
        } else {
            
        }
        
        $conn->close();
        return $this->tags;
    }
    public function getAllCats(){
    
        $this->all_cats = array();
        
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM catbase";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $this->all_cats[] = ["catname" => $row["catname"], "catid" => $row["catid"], "active" => false];
            }
        } else {
            // 0 results
        }
        $conn->close();
        return $this->all_cats;
    }
    public function getCat($postid){
        $this->cat = array();
        
        if ($postid == null) {
            echo "postid is null";
        }
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT catid, catname FROM catblogview WHERE postid = $postid";
        
        $result = $conn->query($sql);
        
        if ($result != false) {
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $this->cat[] = ["catid" => $row["catid"], "catname" => $row["catname"]];
                }
            } else {
                // 0 results
            }
        } else {
            
        }
        
        $conn->close();
        return $this->cat;
    }
    public function initPosts(){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM blogbase";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $post = new Post();
                
                $post->title = $row["posttitle"];
                $post->id = $row["id"];
                $post->content = $row["content"];
                $post->shortpreview = $row["shortpreview"];
                $post->preview = $row["preview"];
                $post->category = $row["category"];
                $post->draft = $row["draft"];
                $post->lastedited = $row["lastedited"];
                $post->author = $row["author"];
                $post->publishdate = $row["publishdate"];
                $post->permalink = $row["permalink"];
                
                $this->posts[] = $post;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }
    public function getPostsByCat($catid){
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM blogbase WHERE category = $catid";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $post = new Post();
                
                $post->title = $row["posttitle"];
                $post->id = $row["id"];
                $post->content = $row["content"];
                $post->shortpreview = $row["shortpreview"];
                $post->preview = $row["preview"];
                $post->category = $row["category"];
                $post->draft = $row["draft"];
                $post->lastedited = $row["lastedited"];
                $post->author = $row["author"];
                $post->publishdate = $row["publishdate"];
                $post->permalink = $row["permalink"];
                
                $this->posts[] = $post;
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        return $this->posts;
    }
    public function initMorePosts(){
        
    }
    public function initTags(){
        
    }
}

mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
