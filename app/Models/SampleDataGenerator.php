<?php

namespace Models;

use Faker;

class SampleDataGenerator
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var Faker\Factory
     */
    protected $faker;

    public function __construct($handler)
    {
        $this->db = $handler;
        $this->faker = Faker\Factory::create();
    }

    public function generateAll($itemNumbers = 10)
    {
        $this->generateCountries($itemNumbers);
        $this->generateDisciplines($itemNumbers);
        $this->generateHomeWorks($itemNumbers);
        $this->generateLocations($itemNumbers);
        $this->generateFaculties($itemNumbers);
        $this->generateUniversities($itemNumbers);

        // ******** GENERATE LOCATION *********

        $departmentData = [];
        $facultyData = [];
        $facultyDisciplinesData = [];
        $facultyStudentsData = [];
        $staffTypesData = [];
        $studentsData = [];
        $studentsHomeWorkData = [];
        $UniversitiesData = [];
    }

    public function generateCountries($itemNumbers = 10)
    {
        $table = 'countries';
        $data = [];
        for ($i = 0; $i < $itemNumbers; ++$i) {
            $data[] = "(
                {$this->db->quote($this->faker->country)},
                {$this->db->quote($this->faker->countryCode)},
                {$this->db->quote($this->faker->imageUrl(50, 'png'))}
            )";
        }
        $sqlQuery = "INSERT INTO `{$table}` (`name`, `iso_code`, `flag`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateDisciplines($itemNumbers = 10)
    {
        $table = 'disciplines';
        $data = [];
        for ($i = 0; $i < $itemNumbers; ++$i) {
            $data[] = "(
                {$this->db->quote($this->faker->sentence(6, true))},
                {$this->db->quote($this->faker->paragraphs(4, true))}
            )";
        }
        $sqlQuery = "INSERT INTO `{$table}` (`name`, `description`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateHomeWorks($itemNumbers = 10)
    {
        $table = 'home_works';
        $data = [];
        for ($i = 0; $i < $itemNumbers; ++$i) {
            $data[] = "(
                {$this->db->quote($this->faker->sentence(3, true))},
                {$this->db->quote($this->faker->paragraphs(2, true))},
                {$this->db->quote($this->faker->date('Y-m-d', 'now').' '.$this->faker->time('H:i:s', 'now'))}
            )";
        }
        $sqlQuery = "INSERT INTO `{$table}` (`name`, `description`, `deadline`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateLocations($itemNumbers = 10)
    {
        $table = 'locations';
        $data = [];
        $countries = new Countries($this->db);
        $countriesIDs = $countries->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndCountryId = $countriesIDs[rand(0, count($countriesIDs) - 1)];
            $data[] = "(
                {$rndCountryId},
                {$this->db->quote($this->faker->city)},
                {$this->db->quote($this->faker->postcode)}
            )";
        }
        unset($countries);

        $sqlQuery = "INSERT INTO `{$table}` (`country_id`, `city`, `postcode`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateFaculties($itemNumbers = 10)
    {
        $table = 'faculties';
        $data = [];
        $locations = new Locations($this->db);
        $locationsIDs = $locations->idAll();
        $staffTypes = new StaffTypes($this->db);
        $staffTypeIDs = $staffTypes->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $gender = $this->getRndGender();
            $rndLocationsID = $locationsIDs[rand(0, count($locationsIDs) - 1)];
            $rndStaffTypeID = $staffTypeIDs[rand(0, count($staffTypeIDs) - 1)];
            $data[] = "(
                {$this->db->quote($this->faker->numerify('##########'))},
                {$rndLocationsID},
                {$rndStaffTypeID},
                {$this->db->quote($this->faker->firstName($gender))},
                {$this->db->quote($this->faker->lastName)},
                {$this->db->quote(ucfirst($gender[0]))},
                {$this->db->quote($this->faker->title($gender))},
                {$this->db->quote($this->faker->e164PhoneNumber)},
                {$this->db->quote($this->faker->safeEmail)},
                {$this->db->quote($this->faker->date('Y-m-d', 'now').' '.$this->faker->time('H:i:s', 'now'))},
                {$this->db->quote($this->faker->imageUrl(200,200))},
                {$this->db->quote($this->faker->paragraphs(rand(2,9), true))}
            )";
        }
        unset($locations);
        unset($staffTypes);

        $sqlQuery = "
            INSERT INTO `{$table}` (`inn`, `location_id`, `staff_type_id`, `first_name`, `last_name`, `sex`,
            `title`, `phone`, `email`, `birthday`, `photo`, `cv`) VALUES 
        ".implode(',', $data).';';
        echo $sqlQuery;
        $this->db->query($sqlQuery);
    }

    public function generateUniversities($itemNumbers = 10)
    {
        $table = 'universities';
        $data = [];
        $locations = new Locations($this->db);
        $locationsIDs = $locations->idAll();
        $faculties = new Faculties($this->db);
        $facultiesIDs = $faculties->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndLocationsID = $locationsIDs[rand(0, count($locationsIDs) - 1)];
            $rndFacultyID = $facultiesIDs[rand(0, count($facultiesIDs) - 1)];
            $data[] = "(
                {$rndLocationsID},
                {$rndFacultyID},
                {$this->db->quote($this->faker->sentence(3, true))},
                {$this->db->quote($this->faker->url)},
                {$this->db->quote($this->faker->imageUrl(200,200))},
                {$this->db->quote($this->faker->paragraphs(2, true))},
                {$this->db->quote($this->faker->paragraphs(5, true))},
                {$this->db->quote($this->faker->date('Y-m-d', 'now'))}
            )";
        }
        unset($locations);
        unset($faculties);
        $sqlQuery = "INSERT INTO `{$table}` (`location_id`, `head_id`, `name`, `url`, `logo`, `description`, `history`,
                    `foundation_date`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateStudents($itemNumbers = 10)
    {
        $table = 'students';
        $data = [];
        $locations = new Locations($this->db);
        $locationsIDs = $locations->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndLocationsID = $locationsIDs[rand(0, count($locationsIDs) - 1)];
            $gender = $this->getRndGender();
            $data[] = "(
                {$rndLocationsID},
                {$this->db->quote($this->faker->firstName($gender))},
                {$this->db->quote($this->faker->lastName)},
                {$this->db->quote(ucfirst($gender[0]))},
                {$this->db->quote($this->faker->title($gender))},
                {$this->db->quote($this->faker->e164PhoneNumber)},
                {$this->db->quote($this->faker->safeEmail)},
                {$this->db->quote($this->faker->date('Y-m-d', 'now').' '.$this->faker->time('H:i:s', 'now'))},
                {$this->db->quote($this->faker->imageUrl(200,200))},
                {$this->db->quote($this->faker->paragraphs(rand(2,9), true))}
            )";
        }
        unset($locations);

        $sqlQuery = "INSERT INTO `{$table}` (`location_id`, `head_id`, `name`, `url`, `logo`, `description`, `history`,
                    `foundation_date`) VALUES ".
            implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    /**
     * @return string randomly generated 'male' or 'female'
     */
    protected function getRndGender()
    {
        $genderType = ['male', 'female'];
        return $genderType[rand(0,1)];
    }
}
