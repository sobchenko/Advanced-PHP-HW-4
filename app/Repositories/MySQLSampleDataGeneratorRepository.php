<?php

namespace Repositories;

use Faker;
use Models\Country;
use Models\Department;
use Models\Discipline;
use Models\Faculty;
use Models\Location;
use Models\StaffType;
use Models\University;

class MySQLSampleDataGeneratorRepository
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
        $this->generateCountries(ceil($itemNumbers / 3));
        $this->generateDisciplines($itemNumbers);
        $this->generateHomeWorks($itemNumbers);
        $this->generateLocations($itemNumbers);
        $this->generateFaculties(2 * $itemNumbers);
        $this->generateUniversities($itemNumbers);
        $this->generateStudents(5 * $itemNumbers);
        $this->generateDepartments($itemNumbers);
        $this->generateFacultyDepartmentCorrespondence();
        $this->generateFacultyDisciplineCorrespondence();
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
        $sqlQuery = "INSERT INTO `{$table}` (`name`, `iso_code`, `flag`) VALUES ".implode(',', $data).';';
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
        $sqlQuery = "INSERT INTO `{$table}` (`name`, `description`) VALUES ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateHomeWorks($itemNumbers = 10)
    {
        $table = 'home_works';
        $disciplineRepository = new CountryRepository($this->db, Discipline::class, 'disciplines');
        $disciplineIDs = $disciplineRepository->idAll();
        $data = [];
        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndDisciplineID = $disciplineIDs[rand(0, count($disciplineIDs) - 1)];
            $data[] = "(
                {$rndDisciplineID},
                {$this->db->quote($this->faker->sentence(3, true))},
                {$this->db->quote($this->faker->paragraphs(2, true))},
                {$this->db->quote($this->faker->date('Y-m-d', 'now').' '.$this->faker->time('H:i:s', 'now'))}
            )";
        }
        $sqlQuery = "INSERT INTO `{$table}` (`discipline_id`, `name`, `description`, `deadline`) VALUES ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateLocations($itemNumbers = 10)
    {
        $table = 'locations';
        $countryRepository = new CountryRepository($this->db, Country::class, 'countries');
        $countryIDs = $countryRepository->idAll();
        $data = [];
        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndCountryId = $countryIDs[rand(0, count($countryIDs) - 1)];
            $data[] = "(
                {$rndCountryId},
                {$this->db->quote($this->faker->city)},
                {$this->db->quote($this->faker->postcode)}
            )";
        }
        unset($countryRepository);

        $sqlQuery = "INSERT INTO `{$table}` (`country_id`, `city`, `postcode`) VALUES ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateFaculties($itemNumbers = 10)
    {
        $table = 'faculties';
        $data = [];
        $locationRepository = new LocationRepository($this->db, Location::class, 'locations');
        $locationIDs = $locationRepository->idAll();
        $staffTypeRepository = new StaffTypeRepository($this->db, StaffType::class, 'staff_types');
        $staffTypeIDs = $staffTypeRepository->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $gender = $this->getRndGender();
            $rndLocationsID = $locationIDs[rand(0, count($locationIDs) - 1)];
            $rndStaffTypeID = $staffTypeIDs[rand(0, count($staffTypeIDs) - 1)];
            $fName = $this->faker->firstName($gender);
            $lName = $this->faker->lastName;
            $data[] = "(
                {$this->db->quote($this->faker->numerify('##########'))},
                {$rndLocationsID},
                {$rndStaffTypeID},
                {$this->db->quote($fName)},
                {$this->db->quote($lName)},
                {$this->db->quote(ucfirst($gender[0]))},
                {$this->db->quote($this->faker->title($gender))},
                {$this->db->quote($this->faker->e164PhoneNumber)},
                {$this->db->quote($this->getEmail($fName, $lName))},
                {$this->db->quote($this->faker->date('Y-m-d', 'now').' '.$this->faker->time('H:i:s', 'now'))},
                {$this->db->quote($this->faker->imageUrl(200, 200))},
                {$this->db->quote($this->faker->paragraphs(rand(2, 9), true))}
            )";
        }
        unset($locationRepository);
        unset($staffTypeRepository);

        $sqlQuery = "
            INSERT INTO `{$table}` (`inn`, `location_id`, `staff_type_id`, `first_name`, `last_name`, `sex`,
            `title`, `phone`, `email`, `birthday`, `photo`, `cv`) 
            VALUES 
        ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateUniversities($itemNumbers = 10)
    {
        $table = 'universities';
        $data = [];
        $locationRepository = new LocationRepository($this->db, Location::class, 'locations');
        $locationIDs = $locationRepository->idAll();
        $facultyRepository = new FacultyRepository($this->db, Faculty::class, 'faculties');
        $facultyIDs = $facultyRepository->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndLocationsID = $locationIDs[rand(0, count($locationIDs) - 1)];
            $rndFacultyID = $facultyIDs[rand(0, count($facultyIDs) - 1)];
            $data[] = "(
                {$rndLocationsID},
                {$rndFacultyID},
                {$this->db->quote($this->faker->sentence(3, true))},
                {$this->db->quote($this->faker->url)},
                {$this->db->quote($this->faker->imageUrl(200, 200))},
                {$this->db->quote($this->faker->paragraphs(2, true))},
                {$this->db->quote($this->faker->paragraphs(5, true))},
                {$this->db->quote($this->faker->date('Y-m-d', 'now'))}
            )";
        }
        unset($locationRepository);
        unset($facultyRepository);
        $sqlQuery = "
            INSERT INTO `{$table}` (`location_id`, `head_id`, `name`, `url`, `logo`, `description`, `history`,
            `foundation_date`) 
            VALUES 
        ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateStudents($itemNumbers = 10)
    {
        $table = 'students';
        $data = [];
        $locationRepository = new LocationRepository($this->db, Location::class, 'locations');
        $locationIDs = $locationRepository->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndLocationID = $locationIDs[rand(0, count($locationIDs) - 1)];
            $gender = $this->getRndGender();
            $fName = $this->faker->firstName($gender);
            $lName = $this->faker->lastName;
            $data[] = "(
                {$rndLocationID},
                {$this->db->quote($fName)},
                {$this->db->quote($lName)},
                {$this->db->quote(ucfirst($gender[0]))},
                {$this->db->quote($this->faker->imageUrl(300, 300))},
                {$this->db->quote($this->getEmail($fName, $lName))},
                {$this->db->quote($this->faker->e164PhoneNumber)}
            )";
        }
        unset($locationRepository);
        $sqlQuery = "
            INSERT INTO `{$table}` (`location_id`, `first_name`, `last_name`, `sex`, `avatar`, `email`, `phone`) 
            VALUES 
        ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateDepartments($itemNumbers = 10)
    {
        $table = 'departments';
        $data = [];
        $universityRepository = new UniversityRepository($this->db, University::class, 'universities');
        $universityIDs = $universityRepository->idAll();
        $facultyRepository = new FacultyRepository($this->db, Faculty::class, 'faculties');
        $facultyIDs = $facultyRepository->idAll();

        for ($i = 0; $i < $itemNumbers; ++$i) {
            $rndUniversityID = $universityIDs[rand(0, count($universityIDs) - 1)];
            $rndFacultyID = $facultyIDs[rand(0, count($facultyIDs) - 1)];
            $data[] = "(
                {$rndFacultyID},
                {$rndUniversityID},
                {$this->db->quote($this->faker->sentence(rand(1, 2), true))},
                {$this->db->quote($this->faker->paragraphs(rand(2, 6), true))}
            )";
        }
        unset($locations);
        $sqlQuery = "
            INSERT INTO `{$table}` (`head_id`, `university_id`, `name`, `description`) 
            VALUES 
        ".implode(',', $data).';';
        $this->db->query($sqlQuery);
    }

    public function generateFacultyDepartmentCorrespondence()
    {
        $facultyRepository = new FacultyRepository($this->db, Faculty::class, 'faculties');
        $facultyIDs = $facultyRepository->getAllFacultiesWithoutDepartment();
        $departmentRepository = new DepartmentRepository($this->db, Department::class, 'departments');
        $departmentIDs = $departmentRepository->idAll();
        $data = [];
        for ($i = 0; $i < count($facultyIDs); ++$i) {
            $rndDepartmentIDs = $departmentIDs[rand(0, count($departmentIDs) - 1)];
            $data[] = "({$facultyIDs[$i]->id}, {$rndDepartmentIDs}, 1)";
        }
        unset($facultyRepository);
        unset($departmentRepository);
        $table = 'faculties_departments';
        if (!empty($data)) {
            $sqlQuery = "
                INSERT INTO `{$table}` (`faculty_id`, `department_id`, `active`) 
                VALUES 
            ".implode(',', $data).';';
            $this->db->query($sqlQuery);
        }
    }

    public function generateFacultyDisciplineCorrespondence()
    {
        $facultyRepository = new FacultyRepository($this->db, Faculty::class, 'faculties');
        $facultyIDs = $facultyRepository->getAllFacultiesWithoutDisciplines();
        $disciplineRepository = new DisciplineRepository($this->db, Discipline::class, 'disciplines');
        $disciplineIDs = $disciplineRepository->idAll();
        $data = [];
        for ($i = 0; $i < count($facultyIDs); ++$i) {
            $o = rand(2, 10);
            for ($j = 0; $j < $o; ++$j) {
                $rndDisciplineIDs = $disciplineIDs[rand(0, count($disciplineIDs) - 1)];
                $data[] = "({$facultyIDs[$i]->id}, {$rndDisciplineIDs}, 1)";
            }
        }
        unset($facultyRepository);
        unset($departmentRepository);
        $table = 'faculties_disciplines';
        if (!empty($data)) {
            $sqlQuery = "
                INSERT INTO `{$table}` (`faculty_id`, `discipline_id`, `active`) 
                VALUES 
            ".implode(',', $data).';';
            $this->db->query($sqlQuery);
        }
    }

    /**
     * @return string randomly generated 'male' or 'female'
     */
    protected function getRndGender()
    {
        $genderType = ['male', 'female'];

        return $genderType[rand(0, 1)];
    }

    /**
     * @return string Randomly generated email address using $firstName and $LastName
     */
    protected function getEmail($firstName, $LastName)
    {
        $mailDomains = [
            'gmail.com',
            'ucla.edu',
            'harvard.edu',
            'mit.edu',
            'brown.edu',
            'stanford.edu',
            'berkeley.edu',
        ];

        return strtolower($firstName.'.'.$LastName).'@'.$mailDomains[rand(0, count($mailDomains) - 1)];
    }
}
