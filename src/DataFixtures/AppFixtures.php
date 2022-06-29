<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $a = array(
        (new Country())
            ->setName("Polska")
            ->setFlag("poland.svg")
            ->setCapital("Warszawa"),

        (new Country())
            ->setName("Anglia")
            ->setFlag("england.svg")
            ->setCapital("Londyn"),

        (new Country())
            ->setName("Hiszpania")
            ->setFlag("spain.svg")
            ->setCapital("Madryt"),

        (new Country())
            ->setName("Niemcy")
            ->setFlag("germany.svg")
            ->setCapital("Berlin"),

        (new Country())
            ->setName("Francja")
            ->setFlag("france.svg")
            ->setCapital("Paryż"),

        (new Country())
            ->setName("Włochy")
            ->setFlag("italy.svg")
            ->setCapital("Rzym"),

        (new Country())
            ->setName("Czechy")
            ->setFlag("czech-republic.svg")
            ->setCapital("Praga"),

        (new Country())
            ->setName("Austria")
            ->setFlag("france.svg")
            ->setCapital("Wiedeń"),
        (new Country())
            ->setName("Ukraina")
            ->setFlag("ukraine.svg")
            ->setCapital("Kijów"),
        (new Country())
            ->setName("Dania")
            ->setFlag("denmark.svg")
            ->setCapital("Kopenhaga")
        );
        foreach ($a as $val){
            $manager->persist($val);
        }
        $manager->flush();
    }
}
