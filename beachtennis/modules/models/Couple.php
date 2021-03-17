<?php

// TODO: I think Team is a better name
class Couple {

    private $id;
    private $name;
    private $player1;
    private $player2;
    private $under;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPlayer1() {
        return $this->player1;
    }

    public function setPlayer1($player1) {
        $this->player1 = $player1;
    }

    public function getPlayer2() {
        return $this->player2;
    }

    public function setPlayer2($player2) {
        $this->player2 = $player2;
    }

    public function getUnder() {
        return $this->under;
    }

    public function setUnder($under) {
        $this->under = $under;
    }

}