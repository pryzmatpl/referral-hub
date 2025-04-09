<?php

use Phinx\Seed\AbstractSeed;

class CompanySeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'TechNova Solutions',
                'description' => 'TechNova Solutions is a global leader in providing innovative software solutions tailored for businesses of all sizes. We specialize in building scalable, secure, and high-performance applications that streamline operations, enhance productivity, and drive digital transformation. Our team of skilled engineers and designers work with clients across industries to create custom software solutions, from enterprise-grade systems to mobile applications, leveraging cutting-edge technologies to meet the unique needs of our clients.',
                'currency' => 'USD',
                'posterId' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'GreenFuture Energy',
                'description' => 'GreenFuture Energy is a pioneering company at the forefront of the renewable energy industry. We are committed to providing sustainable energy solutions that help businesses and communities reduce their carbon footprint and contribute to a greener, more sustainable future. Specializing in solar, wind, and hydroelectric energy, we design and implement cutting-edge renewable energy systems that empower our clients to harness the power of nature. Our mission is to promote environmental stewardship and support the global transition to clean, renewable energy sources, driving economic growth while protecting the planet for future generations.',
                'currency' => 'EUR',
                'posterId' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'AeroTech Dynamics',
                'description' => 'AeroTech Dynamics is a world-class aerospace engineering and defense technology company, dedicated to advancing the future of flight, exploration, and national security. We specialize in the development of cutting-edge aircraft, propulsion systems, and defense solutions that are designed to meet the most stringent performance and safety standards. Our team of expert engineers, scientists, and designers are pushing the boundaries of innovation in aerodynamics, materials science, and avionics to deliver products that revolutionize both the aerospace and defense sectors. Whether it\'s creating next-generation fighter jets, unmanned aerial vehicles (UAVs), or advanced propulsion technologies, AeroTech Dynamics is leading the charge in aerospace excellence.',
                'currency' => 'GBP',
                'posterId' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('companies')->insert($data)->saveData();
    }
}
