<?php
namespace Easylib;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 17:59
 */

use Easylib\Constants;
use Easylib\Config;
use Easylib\Database;

class Application{
    public function help(){
        return Constants::$HELP_TEXT;
    }

    public function scan(){

        $list = array('10000 BC KLAXXON',
            '2012.2009.720p.BluRay.x264-METiS',
            '2 Guns (2013) [1080p]',
            '47 Ronin (2013) [1080p]',
            '50 First Dates (2004) [1080p]',
            'Abraham Lincoln vs Zombies (2012)',
            'Alice.In.Wonderland.2010.SWESUB.TS.XViD-Sabelma',
            'Alvin.and.the.Chipmunks.The.Squeakquel.DVDRip.XviD-RUBY',
            'American.Gangster.2007.DVDSCR.SWESUB.XViD',
            'American History X 1998 XviD DVDRip. Swesub. prien',
            'American Hustle 2013 720p BrRip x264 Pimp4003',
            'American Pie 1[1999]',
            'American Pie 2[2001]NeRoZ',
            'American Pie 3 The Wedding[2003]NeRoZ',
            'American Pie 4 Band Camp[2005]NeRoZ',
            'American Pie 5 The Naked Mile[2006]NeRoZ',
            'American.Pie.Beta.House.[2007]NeRoZ',
            'American Pie - Book of Love',
            'American.Reunion.UNRATED.720p.BluRay.X264-BLOW',
            'Arn.Riket.vid.vagens.slut.2008.SWESUB.DVDRip.XviD.Daisyskin.avi',
            'Artic.blast',
            'Avatar 2009 1080p BluRay X264-AMIABLE',
            'Barely Legal 2011 720p BluRay DTS x264-CHD [EtHD]',
            'Basic.Instinct.2[2006][Unrated.Edition]DvDrip[Eng]-aXXo',
            'Battleship.2012.PROPER.720p.BluRay.x264-SPARKS [PublicHD]',
            'Bolt (2008)',
            'Brave.2012.720p.BluRay.x264-HDChina [PublicHD]',
            'Captain Phillips (2013) [1080p]',
            'Cars.2.720p.BluRay.x264-METiS',
            'Chronicle (2012) [1080p]',
            'Closer (2004)',
            'Cloud Atlas (2012) [1080p]',
            'Colombiana.2011.KORSUB.720p.HDRip.x264.AC3-ZERO',
            'Crazy,Stupid,Love.2011..1080p.Bluray.x264.anoXmous',
            'Death.Race[2008][Unrated.Edition]DvDrip-aXXo',
            'defince',
            'Delivery.Man.2013.720p.BluRay.x264-SPARKS[rarbg]',
            'Despicable Me 2 (2013) [1080p]',
            'Despicable Me DVDRip XviD-iMBT',
            'Die hard 4.0',
            'Disaster.Movie.2008.SWESUB.DVDRip.XviD-Pride86',
            'Disconnect (2012) [1080p]',
            'District 9',
            'Disturbia[2007]DvDrip[Eng]-aXXo',
            'Divergent (2014) 720p BluRay [G2G]',
            'Django Unchained (2012) [1080p]',
//'DOA.Dead.or.Alive.Swesub.Dvdrip.Xvid.BANANA',
            'Don.Jon.2013.720p.WEB-DL.DD5.1.h.264-fiend',
            'Doomsday.2008.NORDiC.PAL.DVDR',
            'Dragonball Evolution(2009)DVDrip[UKB-RG Xvid]-keltz',
            'Elysium.2013.1080p.BluRay.x264.SPARKS.DUAL-BRENYS',
            'Epic (2013)',
            'Eragon 2006 BluRay 720p DTS x264-3Li',
            'Escape from Planet Earth (2013)',
            'Fantastic.Four-Rise.Of.The.Silver.Surfer.DVDRip.XviD.SweSub-Pitbull',
            'Fast.and.Furious.2009.PROPER.SWESUB.R5.LINE.XviD-Sabelma',
            'Fast & Furious - Tokyo Drift',
            'Fearless 2006 720p HDDVD x264-CtrlHD',
            'Fighter.In.The.Wind.2004.XviD.DTS.3CD-WAF',
            'Fired.Up.2009.UNRATED.DVDRiP.XViD',
            'FRIENDS WITH BENEFITS Bluray Rip 1080P HD[danhuk2k13][kickass]',
            'Frozen (2013) [1080p]',
            'Gamer (2009) TS XviD-MAXSPEED',
            'Ghost Rider.DVDRip.XviD.SWESUB',
            'G.I. Joe_ Retaliation (2013) [1080p]',
            'Gone.In.60.Seconds.2000.SWESUB.DVDRip.XviD-Oliver',
            'Gravity (2013) 720p DVDScr x264 Eng-Sub',
            'Hackers.1995.PAL.DVDr-FoA',
            'Halo.Wars.2009.DVDRip.XviD-ViSiON',
            'Hansel and Gretel Witch Hunters (2013) [1080p]',
            'Harry Potter and the Chamber of Secrets (2002) [1080p]',
            'Harry Potter and the Deathly Hallows Part 1 (2010) [1080p]',
            'Harry Potter and the Deathly Hallows Part 2 (2011) 1080p.BRrip.scOrp.sujaidr (pimprg)',
            'Harry Potter and the Goblet of Fire (2005) [1080p]',
            'Harry Potter and the Half Blood Prince (2009) [1080p]',
            'Harry Potter and the Order of the Phoenix (2007) [1080p]',
            'Harry Potter and the Prisoner of Azkaban (2004) [1080p]',
            "Harry Potter and the Sorcerer's Stone (2001)",
            'Hellboy (2004) [1080p]',
            'Hellboy The Golden Army (2008)',
            'Her.2013.720p.BluRay.x264-SPARKS [PublicHD]',
            'Hercules (2014) 1080p Dual Áudio - Douglasvip',
            'Hitman.2007.SWESUB.R5.XviD-FETANKA',
            'Hoodwinked[2005]720p',
            'Hoodwinked Too! Hood vs Evil 2011 720p BRRip [A Release-Lounge H264]',
            'Hotel Transylvania (2012)',
            'How To Train Your Dragon (2010) [1080p]',
            'I.Am.Legend.2007.SWESUB.DVDSCR.XviD-pirat[Tanka fett]',
            'I Am Number Four (2011) DVDRip XviD-MAXSPEED',
            'Ice Age 2002',
            'Ice Age 2006 The Meltdown',
            'Ice Age 2009 Dawn Of The Dinosaurs',
            'Ice Age 2011 A Mammoth Christmas',
            'Immortals 2011 720p RC BRRip LiNE XviD AC3-FTW',
            'Inception (2010) [1080p]',
            'Independence Day.mp4',
            'Inkheart[2008]DvDrip[Eng]-FXG',
            'In.Time.2011.720p.Bluray.x264.DTS-HDC',
            'Iron Man 2008 BDRip H264 AAC-SecretMyth (Kingdom-Release)',
            'Iron Man 2 (2010) [1080p]',
            'Iron Man 3 (2013) [1080p]',
            'Jackass Presents Bad Grandpa(2013)_PapaFatHead.mp4',
            'Jack.Reacher.2012.720p.BluRay.X264-AMIABLE [PublicHD]',
            'James Bond Casino Royale (2006) [1080p]',
            'James Bond Quantum of Solace (2008) [1080p]',
            'Jennifers.Body.DVDRip.XviD-iMBT',
            'Journey.2.The.Mysterious.Island.2012.720p.BluRay.X264-AMIABLE [PublicHD]',
            'Jumper.2008.ENG.DVD.R3.NTSC.DivX-LTT',
            'Jurassic.Park.2.1997.SWESUB.DVDRip.Xvid-pirat',
            'Jurassic.Park.3.2001.SWESUB.DVDRip.Xvid-pirat',
            'Just Go With It (2011) [720p]',
            'Kick-Ass (2010) SWESUB.R5.XviD-ARiA',
            'KickAss 2 (2013) 1080p x264 DD5.1 EN NL Subs [Asian Torrenz]',
            'Kill Bill Volume 1 2003 .avi',
            'Kill.the.Irishman.2011.DVDRip.AC3.XviD-CM8',
            'Kingdom.Of.Heaven[Special.Extended.Directors.Cut]DvDrip[Eng]-aXXo',
            'Kiss.The.Girls.1997.MULTISUBS.PAL.DVDr',
            'Kung Fu Panda (2008)',
            'Kung.Fu.Panda.2.720p.BluRay.x264-MHD',
            'Last Vegas (2013) [1080p]',
            'Lesbian.Vampire.Killers.2009.DvDRip-FxM',
            'Life of Pi (2012) [1080p]',
            'Limitless (2011) 1080p',
            'Looper (2012) [1080p]',
            'Lord.Of.War.2006.XviD.SWESUB-Mojen',
            'Lucky Luke',
            'Madagascar Escape 2 Africa.avi',
            'Man of Steel (2013) [1080p]',
            'Man on a Legde (2012)',
            'Meet.Dave.2008.CUSTOM.SWESUB.DVDR-iNjECT',
            'Meet.The.Spartans.2008.SweSub.DVDRip.XviD-NIKH',
            'Midnight.in.Paris.720p.BluRay.x264-MHD',
            'Monsters University (2013) [1080p]',
            'Mr.Peabody.And.Sherman.2014.720p.BluRay.x264-Felony[rarbg]',
            'National.Treasure.DVDRip.XviD.SweSub-Pitbull',
            'Need.For.Speed.2014.720p.BluRay.x264-SPARKS[rarbg]',
            'Never.Back.Down[2008]DvDrip-aXXo.avi',
            'Night.At.The.Museum.Battle.Of.The.Smithsonian.2009.DvDRip-FxM',
            'Ninja.2009.DVDRip.XviD-LAP',
            'Noah 2014 720p BluRay x264-SPARKS (SilverTorrent)',
            'No Strings Attached (2011) [1080p]',
            'Now You See Me (2013)'
        );

        $db = new Database();
        foreach($list as $movieName){
            $db->getMovies($movieName);
        }

    }

    /**
     * Get or sets a config parameter
     * @param $param An array parameters.
     * To set a config key, the array should have the format array[ {key=value} ]
     * e.g. "TMDB=new_api_key"
     *
     * To get a config key, the array should have the format array[ {key} ]
     *
     * e.g. "TMDB"
     */
    public function config($param){
        $result = "";
        $config = Config::get();
        for($i = 0; $i < count($param); $i++){
            $p = $param[$i];
            //is it a get method? (doesn't contains "=" sign)
            $isGet = !preg_match('/.+\=.+/',$p);
            if($isGet){
                if(array_key_exists($p,$config)){
                    $result = $config[$p] . "\n";
                }
            }else{
                $splited = preg_split('/\=/',$p);
                $field = $splited[0];
                $value = $splited[count($splited) - 1];
                if(array_key_exists($field,$config)){
                    $config[$field] = $value;
                }
            }
        }
        Config::set($config);
        return $result;
    }



}
