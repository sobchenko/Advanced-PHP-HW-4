<?php

namespace Controllers;

use Faker;
use Models\Countries;

class DbsampledataController extends AbstractController
{
    public function index($param = '')
    {
        $view = 'sample_data';
        $message = '';
        $this->view($view, [
            'message' => $message,
            'generate_section' => 1,
            'items_number' => 20,
        ]);
    }

    public function generate($param = '')
    {
        $view = 'sample_data';
        $message = '';

        $items_number = $_POST['items_number'] ? $_POST['items_number'] : 20;

        $faker = Faker\Factory::create();
        $countryData = [];
        $departmentData = [];
        $disciplinesData = [];
        $facultyData = [];
        $facultyDisciplinesData = [];
        $facultyStudentsData = [];
        $homeWorksData = [];
        $locationsData = [];
        $staffTypesData = [];
        $studentsData = [];
        $studentsHomeWorkData = [];
        $UniversitiesData = [];
        for ($i = 0; $i < $items_number; ++$i) {
            $countryData[] = "('{$faker->country}','{$faker->countryCode}','{$faker->imageUrl(50, 'png')}')";
            $disciplinesData[] = "('{$faker->sentence(6, true)}','{$faker->paragraphs(4, true)}')";
        }

        $queryCountries = 'INSERT INTO `countries` (`name`,`iso_code`,`flag`) VALUES '.implode(',', $countryData).';';
        $queryDisciplines = 'INSERT INTO `disciplines` (`name`,`description`) VALUES '.implode(',', $disciplinesData).';';


        $query = $queryCountries . $queryDisciplines;

        $message = $query;

        $sql = $this->db->handler->prepare($query);

        $sql->execute();
//        $countries = new Countries($this->db->handler);

//        var_dump($countries->findAll());

        $this->view($view, [
            'message' => $message,
            'result' => 1,
        ]);
    }
}
