<?php

    Class HomeController extends RenderView {
        public function Index(){
            $this->LoadView('Home',[
                'title' => 'Home Page',
                'description' => 'Essa é a tela principal do sistema.',
            ]);
        }
    }