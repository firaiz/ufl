<?php
namespace UflAs;

include_once __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use PHPUnit\Framework\TestCase;
use UflAs\Holiday\Japan;

class HolidayTest extends TestCase
{
    protected $lists = array(
        1958 => array(
            '1958-01-01' => '元日',
            '1958-01-15' => '成人の日',
            '1958-03-21' => '春分の日',
            '1958-04-29' => '天皇誕生日',
            '1958-05-03' => '憲法記念日',
            '1958-05-05' => 'こどもの日',
            '1958-09-23' => '秋分の日',
            '1958-11-03' => '文化の日',
            '1958-11-23' => '勤労感謝の日',
        ),
        1959 => array(
            '1959-01-01' => '元日',
            '1959-01-15' => '成人の日',
            '1959-03-21' => '春分の日',
            '1959-04-10' => '明仁親王の結婚の儀',
            '1959-04-29' => '天皇誕生日',
            '1959-05-03' => '憲法記念日',
            '1959-05-05' => 'こどもの日',
            '1959-09-24' => '秋分の日',
            '1959-11-03' => '文化の日',
            '1959-11-23' => '勤労感謝の日',
        ),
        1960 => array(
            '1960-01-01' => '元日',
            '1960-01-15' => '成人の日',
            '1960-03-20' => '春分の日',
            '1960-04-29' => '天皇誕生日',
            '1960-05-03' => '憲法記念日',
            '1960-05-05' => 'こどもの日',
            '1960-09-23' => '秋分の日',
            '1960-11-03' => '文化の日',
            '1960-11-23' => '勤労感謝の日',
        ),
        1972 => array(
            '1972-01-01' => '元日',
            '1972-01-15' => '成人の日',
            '1972-02-11' => '建国記念の日',
            '1972-03-20' => '春分の日',
            '1972-04-29' => '天皇誕生日',
            '1972-05-03' => '憲法記念日',
            '1972-05-05' => 'こどもの日',
            '1972-09-15' => '敬老の日',
            '1972-09-23' => '秋分の日',
            '1972-10-10' => '体育の日',
            '1972-11-03' => '文化の日',
            '1972-11-23' => '勤労感謝の日',
        ),
        1973 => array(
            '1973-01-01' => '元日',
            '1973-01-15' => '成人の日',
            '1973-02-11' => '建国記念の日',
            '1973-03-21' => '春分の日',
            '1973-04-29' => '天皇誕生日',
            '1973-04-30' => '振替休日',
            '1973-05-03' => '憲法記念日',
            '1973-05-05' => 'こどもの日',
            '1973-09-15' => '敬老の日',
            '1973-09-23' => '秋分の日',
            '1973-09-24' => '振替休日',
            '1973-10-10' => '体育の日',
            '1973-11-03' => '文化の日',
            '1973-11-23' => '勤労感謝の日',
        ),
        1974 => array(
            '1974-01-01' => '元日',
            '1974-01-15' => '成人の日',
            '1974-02-11' => '建国記念の日',
            '1974-03-21' => '春分の日',
            '1974-04-29' => '天皇誕生日',
            '1974-05-03' => '憲法記念日',
            '1974-05-05' => 'こどもの日',
            '1974-05-06' => '振替休日',
            '1974-09-15' => '敬老の日',
            '1974-09-16' => '振替休日',
            '1974-09-23' => '秋分の日',
            '1974-10-10' => '体育の日',
            '1974-11-03' => '文化の日',
            '1974-11-04' => '振替休日',
            '1974-11-23' => '勤労感謝の日',
        ),
        2006 => array(
            '2006-01-01' => '元日',
            '2006-01-02' => '振替休日',
            '2006-01-09' => '成人の日',
            '2006-02-11' => '建国記念の日',
            '2006-03-21' => '春分の日',
            '2006-04-29' => 'みどりの日',
            '2006-05-03' => '憲法記念日',
            '2006-05-04' => '国民の休日',
            '2006-05-05' => 'こどもの日',
            '2006-07-17' => '海の日',
            '2006-09-18' => '敬老の日',
            '2006-09-23' => '秋分の日',
            '2006-10-09' => '体育の日',
            '2006-11-03' => '文化の日',
            '2006-11-23' => '勤労感謝の日',
            '2006-12-23' => '天皇誕生日',
        ),
        2007 => array(
            '2007-01-01' => '元日',
            '2007-01-08' => '成人の日',
            '2007-02-11' => '建国記念の日',
            '2007-02-12' => '振替休日',
            '2007-03-21' => '春分の日',
            '2007-04-29' => '昭和の日',
            '2007-04-30' => '振替休日',
            '2007-05-03' => '憲法記念日',
            '2007-05-04' => 'みどりの日',
            '2007-05-05' => 'こどもの日',
            '2007-07-16' => '海の日',
            '2007-09-17' => '敬老の日',
            '2007-09-23' => '秋分の日',
            '2007-09-24' => '振替休日',
            '2007-10-08' => '体育の日',
            '2007-11-03' => '文化の日',
            '2007-11-23' => '勤労感謝の日',
            '2007-12-23' => '天皇誕生日',
            '2007-12-24' => '振替休日',
        ),
        2008 => array(
            '2008-01-01' => '元日',
            '2008-01-14' => '成人の日',
            '2008-02-11' => '建国記念の日',
            '2008-03-20' => '春分の日',
            '2008-04-29' => '昭和の日',
            '2008-05-03' => '憲法記念日',
            '2008-05-04' => 'みどりの日',
            '2008-05-05' => 'こどもの日',
            '2008-05-06' => '振替休日',
            '2008-07-21' => '海の日',
            '2008-09-15' => '敬老の日',
            '2008-09-23' => '秋分の日',
            '2008-10-13' => '体育の日',
            '2008-11-03' => '文化の日',
            '2008-11-23' => '勤労感謝の日',
            '2008-11-24' => '振替休日',
            '2008-12-23' => '天皇誕生日',
        ),
        2009 => array(
            '2009-01-01' => '元日',
            '2009-01-12' => '成人の日',
            '2009-02-11' => '建国記念の日',
            '2009-03-20' => '春分の日',
            '2009-04-29' => '昭和の日',
            '2009-05-03' => '憲法記念日',
            '2009-05-04' => 'みどりの日',
            '2009-05-05' => 'こどもの日',
            '2009-05-06' => '振替休日',
            '2009-07-20' => '海の日',
            '2009-09-21' => '敬老の日',
            '2009-09-22' => '国民の休日',
            '2009-09-23' => '秋分の日',
            '2009-10-12' => '体育の日',
            '2009-11-03' => '文化の日',
            '2009-11-23' => '勤労感謝の日',
            '2009-12-23' => '天皇誕生日',
        ),
        2010 => array(
            '2010-01-01' => '元日',
            '2010-01-11' => '成人の日',
            '2010-02-11' => '建国記念の日',
            '2010-03-21' => '春分の日',
            '2010-03-22' => '振替休日',
            '2010-04-29' => '昭和の日',
            '2010-05-03' => '憲法記念日',
            '2010-05-04' => 'みどりの日',
            '2010-05-05' => 'こどもの日',
            '2010-07-19' => '海の日',
            '2010-09-20' => '敬老の日',
            '2010-09-23' => '秋分の日',
            '2010-10-11' => '体育の日',
            '2010-11-03' => '文化の日',
            '2010-11-23' => '勤労感謝の日',
            '2010-12-23' => '天皇誕生日',
        ),
        2011 => array(
            '2011-01-01' => '元日',
            '2011-01-10' => '成人の日',
            '2011-02-11' => '建国記念の日',
            '2011-03-21' => '春分の日',
            '2011-04-29' => '昭和の日',
            '2011-05-03' => '憲法記念日',
            '2011-05-04' => 'みどりの日',
            '2011-05-05' => 'こどもの日',
            '2011-07-18' => '海の日',
            '2011-09-19' => '敬老の日',
            '2011-09-23' => '秋分の日',
            '2011-10-10' => '体育の日',
            '2011-11-03' => '文化の日',
            '2011-11-23' => '勤労感謝の日',
            '2011-12-23' => '天皇誕生日',
        ),
        2012 => array(
            '2012-01-01' => '元日',
            '2012-01-02' => '振替休日',
            '2012-01-09' => '成人の日',
            '2012-02-11' => '建国記念の日',
            '2012-03-20' => '春分の日',
            '2012-04-29' => '昭和の日',
            '2012-04-30' => '振替休日',
            '2012-05-03' => '憲法記念日',
            '2012-05-04' => 'みどりの日',
            '2012-05-05' => 'こどもの日',
            '2012-07-16' => '海の日',
            '2012-09-17' => '敬老の日',
            '2012-09-22' => '秋分の日',
            '2012-10-08' => '体育の日',
            '2012-11-03' => '文化の日',
            '2012-11-23' => '勤労感謝の日',
            '2012-12-23' => '天皇誕生日',
            '2012-12-24' => '振替休日',
        ),
        2013 => array(
            '2013-01-01' => '元日',
            '2013-01-14' => '成人の日',
            '2013-02-11' => '建国記念の日',
            '2013-03-20' => '春分の日',
            '2013-04-29' => '昭和の日',
            '2013-05-03' => '憲法記念日',
            '2013-05-04' => 'みどりの日',
            '2013-05-05' => 'こどもの日',
            '2013-05-06' => '振替休日',
            '2013-07-15' => '海の日',
            '2013-09-16' => '敬老の日',
            '2013-09-23' => '秋分の日',
            '2013-10-14' => '体育の日',
            '2013-11-03' => '文化の日',
            '2013-11-04' => '振替休日',
            '2013-11-23' => '勤労感謝の日',
            '2013-12-23' => '天皇誕生日',
        ),
        2014 => array(
            '2014-01-01' => '元日',
            '2014-01-13' => '成人の日',
            '2014-02-11' => '建国記念の日',
            '2014-03-21' => '春分の日',
            '2014-04-29' => '昭和の日',
            '2014-05-03' => '憲法記念日',
            '2014-05-04' => 'みどりの日',
            '2014-05-05' => 'こどもの日',
            '2014-05-06' => '振替休日',
            '2014-07-21' => '海の日',
            '2014-09-15' => '敬老の日',
            '2014-09-23' => '秋分の日',
            '2014-10-13' => '体育の日',
            '2014-11-03' => '文化の日',
            '2014-11-23' => '勤労感謝の日',
            '2014-11-24' => '振替休日',
            '2014-12-23' => '天皇誕生日',
        ),
        2015 => array(
            '2015-01-01' => '元日',
            '2015-01-12' => '成人の日',
            '2015-02-11' => '建国記念の日',
            '2015-03-21' => '春分の日',
            '2015-04-29' => '昭和の日',
            '2015-05-03' => '憲法記念日',
            '2015-05-04' => 'みどりの日',
            '2015-05-05' => 'こどもの日',
            '2015-05-06' => '振替休日',
            '2015-07-20' => '海の日',
            '2015-09-21' => '敬老の日',
            '2015-09-22' => '国民の休日',
            '2015-09-23' => '秋分の日',
            '2015-10-12' => '体育の日',
            '2015-11-03' => '文化の日',
            '2015-11-23' => '勤労感謝の日',
            '2015-12-23' => '天皇誕生日',
        ),
        2016 => array(
            '2016-01-01' => '元日',
            '2016-01-11' => '成人の日',
            '2016-02-11' => '建国記念の日',
            '2016-03-20' => '春分の日',
            '2016-03-21' => '振替休日',
            '2016-04-29' => '昭和の日',
            '2016-05-03' => '憲法記念日',
            '2016-05-04' => 'みどりの日',
            '2016-05-05' => 'こどもの日',
            '2016-07-18' => '海の日',
            '2016-08-11' => '山の日',
            '2016-09-19' => '敬老の日',
            '2016-09-22' => '秋分の日',
            '2016-10-10' => '体育の日',
            '2016-11-03' => '文化の日',
            '2016-11-23' => '勤労感謝の日',
            '2016-12-23' => '天皇誕生日',
        ),
        2017 => array(
            '2017-01-01' => '元日',
            '2017-01-02' => '振替休日',
            '2017-01-09' => '成人の日',
            '2017-02-11' => '建国記念の日',
            '2017-03-20' => '春分の日',
            '2017-04-29' => '昭和の日',
            '2017-05-03' => '憲法記念日',
            '2017-05-04' => 'みどりの日',
            '2017-05-05' => 'こどもの日',
            '2017-07-17' => '海の日',
            '2017-08-11' => '山の日',
            '2017-09-18' => '敬老の日',
            '2017-09-23' => '秋分の日',
            '2017-10-09' => '体育の日',
            '2017-11-03' => '文化の日',
            '2017-11-23' => '勤労感謝の日',
            '2017-12-23' => '天皇誕生日',
        ),
        2018 => array(
            '2018-01-01' => '元日',
            '2018-01-08' => '成人の日',
            '2018-02-11' => '建国記念の日',
            '2018-02-12' => '振替休日',
            '2018-03-21' => '春分の日',
            '2018-04-29' => '昭和の日',
            '2018-04-30' => '振替休日',
            '2018-05-03' => '憲法記念日',
            '2018-05-04' => 'みどりの日',
            '2018-05-05' => 'こどもの日',
            '2018-07-16' => '海の日',
            '2018-08-11' => '山の日',
            '2018-09-17' => '敬老の日',
            '2018-09-23' => '秋分の日',
            '2018-09-24' => '振替休日',
            '2018-10-08' => '体育の日',
            '2018-11-03' => '文化の日',
            '2018-11-23' => '勤労感謝の日',
            '2018-12-23' => '天皇誕生日',
            '2018-12-24' => '振替休日',
        ),
        2019 => array(
            '2019-01-01' => '元日',
            '2019-01-14' => '成人の日',
            '2019-02-11' => '建国記念の日',
            '2019-03-21' => '春分の日',
            '2019-04-29' => '昭和の日',
            '2019-04-30' => '国民の休日',
            '2019-05-01' => '天皇即位の日',
            '2019-05-02' => '国民の休日',
            '2019-05-03' => '憲法記念日',
            '2019-05-04' => 'みどりの日',
            '2019-05-05' => 'こどもの日',
            '2019-05-06' => '振替休日',
            '2019-07-15' => '海の日',
            '2019-08-11' => '山の日',
            '2019-08-12' => '振替休日',
            '2019-09-16' => '敬老の日',
            '2019-09-23' => '秋分の日',
            '2019-10-14' => '体育の日',
            '2019-10-22' => '即位礼正殿の儀',
            '2019-11-03' => '文化の日',
            '2019-11-04' => '振替休日',
            '2019-11-23' => '勤労感謝の日',
        ),
        2020 => array(
            '2020-01-01' => '元日',
            '2020-01-13' => '成人の日',
            '2020-02-11' => '建国記念の日',
            '2020-02-23' => '天皇誕生日',
            '2020-02-24' => '振替休日',
            '2020-03-20' => '春分の日',
            '2020-04-29' => '昭和の日',
            '2020-05-03' => '憲法記念日',
            '2020-05-04' => 'みどりの日',
            '2020-05-05' => 'こどもの日',
            '2020-05-06' => '振替休日',
            '2020-07-23' => '海の日',
            '2020-07-24' => 'スポーツの日',
            '2020-08-10' => '山の日',
            '2020-09-21' => '敬老の日',
            '2020-09-22' => '秋分の日',
            '2020-11-03' => '文化の日',
            '2020-11-23' => '勤労感謝の日',
        ),
        2021 => array(
            '2021-01-01' => '元日',
            '2021-01-11' => '成人の日',
            '2021-02-11' => '建国記念の日',
            '2021-02-23' => '天皇誕生日',
            '2021-03-20' => '春分の日',
            '2021-04-29' => '昭和の日',
            '2021-05-03' => '憲法記念日',
            '2021-05-04' => 'みどりの日',
            '2021-05-05' => 'こどもの日',
            '2021-07-19' => '海の日',
            '2021-08-11' => '山の日',
            '2021-09-20' => '敬老の日',
            '2021-09-23' => '秋分の日',
            '2021-10-11' => 'スポーツの日',
            '2021-11-03' => '文化の日',
            '2021-11-23' => '勤労感謝の日',
        )
    );
    protected $calcLists = array(
        '2000-03-20' => '春分の日','2000-09-23' => '秋分の日',
        '2001-03-20' => '春分の日','2001-09-23' => '秋分の日',
        '2002-03-21' => '春分の日','2002-09-23' => '秋分の日',
        '2003-03-21' => '春分の日','2003-09-23' => '秋分の日',
        '2004-03-20' => '春分の日','2004-09-23' => '秋分の日',
        '2005-03-20' => '春分の日','2005-09-23' => '秋分の日',
        '2006-03-21' => '春分の日','2006-09-23' => '秋分の日',
        '2007-03-21' => '春分の日','2007-09-23' => '秋分の日',
        '2008-03-20' => '春分の日','2008-09-23' => '秋分の日',
        '2009-03-20' => '春分の日','2009-09-23' => '秋分の日',
        '2010-03-21' => '春分の日','2010-09-23' => '秋分の日',
        '2011-03-21' => '春分の日','2011-09-23' => '秋分の日',
        '2012-03-20' => '春分の日','2012-09-22' => '秋分の日',
        '2013-03-20' => '春分の日','2013-09-23' => '秋分の日',
        '2014-03-21' => '春分の日','2014-09-23' => '秋分の日',
        '2015-03-21' => '春分の日','2015-09-23' => '秋分の日',
        '2016-03-20' => '春分の日','2016-09-22' => '秋分の日',
        '2017-03-20' => '春分の日','2017-09-23' => '秋分の日',
        '2018-03-21' => '春分の日','2018-09-23' => '秋分の日',
        '2019-03-21' => '春分の日','2019-09-23' => '秋分の日',
        '2020-03-20' => '春分の日','2020-09-22' => '秋分の日',
        '2021-03-20' => '春分の日','2021-09-23' => '秋分の日',
        '2022-03-21' => '春分の日','2022-09-23' => '秋分の日',
        '2023-03-21' => '春分の日','2023-09-23' => '秋分の日',
        '2024-03-20' => '春分の日','2024-09-22' => '秋分の日',
        '2025-03-20' => '春分の日','2025-09-23' => '秋分の日',
        '2026-03-20' => '春分の日','2026-09-23' => '秋分の日',
        '2027-03-21' => '春分の日','2027-09-23' => '秋分の日',
        '2028-03-20' => '春分の日','2028-09-22' => '秋分の日',
        '2029-03-20' => '春分の日','2029-09-23' => '秋分の日',
        '2030-03-20' => '春分の日','2030-09-23' => '秋分の日',
    );
    
    const FORMAT = 'Y-m-d';

    protected function setUp()
    {
        Date::init();
    }

    protected function getYearList($year) {
        return isset($this->lists[$year]) ? $this->lists[$year] : array();
    }

    protected function oneYear($year) {
        $lists = $this->getYearList($year);
        $holidays = Japan::listOf($year);
        foreach ($holidays as $holiday) {
            $this->assertArrayHasKey($holiday->getDate(self::FORMAT), $lists, 'list has date');
            $this->assertEquals(
                $holiday->getName(),
                $lists[$holiday->getDate(self::FORMAT)], 'list text equal'.$holiday->getDate(self::FORMAT));
        }

        foreach ($lists as $date => $name) {
            $this->assertArrayHasKey($date, $holidays, 'holidays has date '.$date);

            $holiday = $holidays[$date];
            $this->assertEquals( $name, $holiday->getName(), 'list text equal'.$holiday->getDate(self::FORMAT));
        }
    }

    public function testAll()
    {
        foreach ($this->lists as $year => $list) {
            $this->oneYear($year);
        }
    }

    public function testOneAt2014()
    {
        $this->oneYear(2014);
    }

    public function testOneAt2009()
    {
        $this->oneYear(2009);
    }

    public function testOneAt1959()
    {
        $this->oneYear(1959);
    }

    public function testOneAt1973()
    {
        $this->oneYear(1973);
    }

    public function testOneAt2019()
    {
        $this->oneYear(2019);
    }

    public function testOneAt2020()
    {
        $this->oneYear(2020);
    }

    public function testOneAt2021()
    {
        $this->oneYear(2021);
    }

    public function testCheckSince()
    {
        $this->assertTrue(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => 2020,),new DateTime('2020-02-23')));
        $this->assertFalse(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => 2020,),new DateTime('2019-02-23')));
        $this->assertTrue(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => 2020,), new DateTime('2021-02-23')));
        $this->assertFalse(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => new DateTime('2020-01-02'),), new DateTime('2020-01-01')));
        $this->assertTrue(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => new DateTime('2020-01-02'),), new DateTime('2020-01-02')));
        $this->assertTrue(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => new DateTime('2020-01-02'),), new DateTime('2020-01-02')));
    }

    public function testCheckAbort()
    {
        $this->assertTrue(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => 2020,), new DateTime('2020-02-23')));
        $this->assertTrue(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => 2020,), new DateTime('2019-02-23')));
        $this->assertFalse(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => 2020,), new DateTime('2021-02-23')));
        $this->assertTrue(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => new DateTime('2020-01-01'),), new DateTime('2020-01-01')));
        $this->assertFalse(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => new DateTime('2020-01-01'),), new DateTime('2020-01-02')));
        $this->assertFalse(Japan::isAbortDate(array(Japan::CHECK_TYPE_ABORT => new DateTime('2020-01-01'),), new DateTime('2020-12-31')));
    }

    public function testCheckBoth()
    {
        $this->assertTrue(Japan::isSinceDate(array(Japan::CHECK_TYPE_SINCE => 1959, Japan::CHECK_TYPE_ABORT => 1959), new DateTime('1959-01-01')));
        $this->assertTrue(Japan::isAbortDate(array(Japan::CHECK_TYPE_SINCE => 1959, Japan::CHECK_TYPE_ABORT => 1959), new DateTime('1959-12-31')));
        $this->assertFalse(Japan::isAbortDate(array(Japan::CHECK_TYPE_SINCE => 1959, Japan::CHECK_TYPE_ABORT => 1959), new DateTime('1960-01-01')));
    }

    public function testBoundaryValue()
    {
        foreach (range(1958, 1960) as $year) {
            $this->oneYear($year);
        }
    }

    public function testSubstituteDayOff() {
        foreach (range(1972, 1974) as $year) {
            $this->oneYear($year);
        }
    }

    public function testSpring()
    {
        foreach (range(2000, 2030) as $year) {
            $date = Date::toDate($year, 3, Japan::getSpringEquinoxDay($year));
            $this->assertArrayHasKey($date->format(self::FORMAT), $this->calcLists);
        }
    }

    public function testAutumnal()
    {
        foreach (range(2000, 2030) as $year) {
            $date = Date::toDate($year, 9, Japan::getAutumnalEquinoxDay($year));
            $this->assertArrayHasKey($date->format(self::FORMAT), $this->calcLists);
        }
    }
}
