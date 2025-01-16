<?php

class Course {

    private $tags = [];

        public function __construct(
            private int $id,
            private string $title,
            private string $description,
            private string $content,
            private $category,
            private int $teacher_id
        ){

        }

        public function addTags($tag){
            if(in_array($tag,$this->tags)){
                return 0;
            }
            $this->tags[] = $tag;
            return 1;

        }

        public function getDetails(){
            return [
                    'id' => $this->id,
                    'title' => $this->title,
                    'description' => $this->description,
                    'content' => $this->content,
                    'category' => $this->category,
                    'tags' => $this->tags,
                    'teacher' => $this->teacher_id

            ];
        }

        // public fucntion deletCourse(){

        // }






}






?>