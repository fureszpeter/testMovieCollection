<?php

namespace Controller;

use Collection\CharacterCollection;
use Collection\ContractCollection;
use Entity\Actor;
use Entity\AutoIncrementFile;
use Entity\Movie;
use Furesz\App;
use ValueObject\ActorMovieContract;
use ValueObject\IActorContract;
use ValueObject\MovieCharacter;

class DefaultController {

    //@TODO Implement MVC
    //@TODO Implement CommandBus pattern
    public function indexAction()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "on");
        echo App::getInstance()->getAppRoot();

        $sequenceDb = new AutoIncrementFile(App::getInstance()->getAppRoot() . "/dataStore/sequence.db");

        $dtoForMovie = [
            Movie::FIELD_TITLE => "Nutty Professor",
            Movie::FIELD_RUNTIME => new \DateInterval("PT95M"),
            Movie::FIELD_RELEASE_DATE => new \DateTime("1996")
        ];

        $contractOfEddie = $this->getEddieMurphysContract($sequenceDb);
        $contractOfJudith = $this->getJudithWoodburyContract($sequenceDb);

        //Contracts of Movie
        $contractCollection = new ContractCollection();
        $contractCollection->append($contractOfEddie);
        $contractCollection->append($contractOfJudith);

        $movie = new Movie($sequenceDb, $dtoForMovie, $contractCollection);

        yield var_dump($movie->getActors(SORT_DESC));
        yield var_dump($movie->getActors(SORT_ASC));

        foreach ($movie->getContracts() as $contract){
            /** @var IActorContract $contract*/
            yield "Contract of " . $contract->getActor()->getName() . " <br>\n";
            foreach ($contract->getCharacters() as $character){
                /** @var MovieCharacter $character*/
                yield "Actor acts as " . $character->getName() . " <br>\n";
            }
        }
    }

    /**
     * @param $sequenceDb
     * @return ActorMovieContract
     */
    private function getEddieMurphysContract($sequenceDb)
    {
        $actorEddieMurphy = new Actor($sequenceDb, "Eddie Murphy", \DateTime::createFromFormat("Y", 1961));
        $characterCollectionForEddiesContract = new CharacterCollection();
        $characterCollectionForEddiesContract->append(new MovieCharacter("Shreman Klump"));
        $characterCollectionForEddiesContract->append(new MovieCharacter("Papa Klump"));
        $characterCollectionForEddiesContract->append(new MovieCharacter("Lance Perkins"));
        $characterCollectionForEddiesContract->append(new MovieCharacter("Buddy Love"));
        $contractOfEddie = new ActorMovieContract($actorEddieMurphy, $characterCollectionForEddiesContract);

        return $contractOfEddie;
    }

    /**
     * @param $sequenceDb
     * @return ActorMovieContract
     */
    private function getJudithWoodburyContract($sequenceDb)
    {
        $actorJudithWoodbury = new Actor($sequenceDb, "Judith Woodbury", \DateTime::createFromFormat("Y", 1922));
        $characterCollectionForJudithContract = new CharacterCollection();
        $characterCollectionForJudithContract->append(new MovieCharacter("Wellmann College Alumni"));
        $contractOfJudith = new ActorMovieContract($actorJudithWoodbury, $characterCollectionForJudithContract);

        return $contractOfJudith;
    }
}