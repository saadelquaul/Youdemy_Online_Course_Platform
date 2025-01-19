
<?php

class Course {

    private $id;
    private $title;
    private $description;
    private $content;
    private $category_id;
    private $tags = [];

        public function __construct($title,$description,$content,$category_id)
        {
                $this->title = $title;
                $this->description = $description;
                $this->content = $content;
                $this->category_id = $category_id;
        }

        public function addTags($tag){
            if(in_array($tag,$this->tags)){
                return 0;
            }
            $this->tags[] = $tag;
            return 1;

        }

        public function setID($id){
            $this->id = $id;
        }
        public function getID($id){
            return $this->id;
        }

        public function getTitle(){
            return $this->title;

        }
        public function getDescription(){
            return $this->description;
        }
        public function getContent(){
            return $this->content;
        }
        public function getCategoryID(){
            return $this->category_id;
        }
        public function getTags(){
            return $this->tags;
        }
        public function getDetails(){
            return [
                    'id' => $this->id,
                    'title' => $this->title,
                    'description' => $this->description,
                    'content' => $this->content,
                    'category' => $this->category_id,
                    'tags' => $this->tags,
                    // 'teacher' => $this->teacher_id

            ];
        }

        public function update(){
            
        }

        // public fucntion deletCourse(){

        // }

        





}






?>