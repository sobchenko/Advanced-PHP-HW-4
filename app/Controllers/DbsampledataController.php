<?php

namespace Controllers;

use Faker;

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
        }

        $queryCountries = 'INSERT INTO `countries` (`name`,`iso_code`,`flag`) VALUES '.implode(',', $countryData).';';

        $query = $queryCountries;

        $message = $query;

        $sql = $this->db->handler->prepare($query);
        $sql->execute();

        $this->view($view, [
            'message' => $message,
            'result' => 1,
        ]);
    }
}
