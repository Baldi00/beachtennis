<?php

class Player {

    private $id;
    private $name;
    private $birthdayDate;   // TODO: birthday or birthDate are better
    private $phone;
    private $subscribed;     // TODO: what does it mean?

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getBirthdayDate() {
        return $this->birthdayDate;
    }

    public function setBirthdayDate($birthdayDate) {
        $this->birthdayDate = $birthdayDate;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getSubscribed() {
        return $this->subscribed;
    }

    public function setSubscribed($subscribed) {
        $this->subscribed = $subscribed;
    }

}