[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/liamtoaldo/Metropolitana">
    <img src="public/images/logo.png" alt="Logo" width="200">
  </a>
</p>
<h1 align="center">Metropolitana</h1>

  <p align="center">
    PHP web app to manage subways and book tickets using a MySQL Database and Graph structures. Inspired by and using stations from <b>Rome</b>'s ATAC.
    <br />
    <br />
    <a href="https://github.com/liamtoaldo/Metropolitana/issues">Report Bug</a> || 
    <a href="https://github.com/liamtoaldo/Metropolitana/pulls">Request Feature</a>
  </p>

<!-- TABLE OF CONTENTS -->

## Table of Contents

-   [About the Project](#about-the-project)
    -   [Built With](#built-with)
-   [Getting Started](#getting-started)
    -   [Prerequisites](#prerequisites)
    -   [Installation](#installation)
-   [Usage](#usage)
-   [Roadmap](#roadmap)
-   [Contributing](#contributing)
-   [License](#license)
-   [Contact](#contact)
-   [Acknowledgements](#acknowledgements)

## About The Project

This was an end-of-year school project web app originally designed to manage subways and book tickets using a MySQL Database and Graph structures. The project is inspired by and uses stations from Rome's ATAC subway system.
It does not have all of the stations and lines, and the trains are fictional. I do not own the rights to the ATAC name, nor the AS Roma's logo. I take no responsibility for you using this outside of personal use.

### Built With

-   PHP
-   MySQL Database
-   Graph structures
-   Materialize (CSS framework)

<!-- GETTING STARTED -->

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

-   PHP
-   MySQL

### Installation

1. Clone the repository
   ```sh
   git clone https://github.com/liamtoaldo/Metropolitana.git
   ```
2. Configure the MySQL database connection in config.php
    ```php
    //Replace the values in the /src/config.php file
    ```
3. Create the schema and the database, using the `dumpStructure.sql` file
4. Add the data using the `dumpData.sql` file
5. Start a PHP server or use a web server of your choice to run the application
## Usage

Instructions on how to use the application and interact with its features.
<!-- ROADMAP -->
## Roadmap

See the open issues for a list of proposed features (and known issues).
<!-- CONTRIBUTING -->
## Contributing
Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are greatly appreciated.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<!-- LICENSE -->
## License

Distributed under the MIT License. See LICENSE for more information.
<!-- CONTACT -->
## Contact

Me - [liamtoaldo+gh@gmail.com](mailto:liamtoaldo+gh@gmail.com)

Project Link: https://github.com/liamtoaldo/Metropolitana
<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
- ATAC
- Materialize

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/liamtoaldo/Metropolitana.svg?style=flat-square
[contributors-url]: https://github.com/liamtoaldo/Metropolitana/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/liamtoaldo/Metropolitana.svg?style=flat-square
[forks-url]: https://github.com/liamtoaldo/Metropolitana/network/members
[stars-shield]: https://img.shields.io/github/stars/liamtoaldo/Metropolitana.svg?style=flat-square
[stars-url]: https://github.com/liamtoaldo/Metropolitana/stargazers
[issues-shield]: https://img.shields.io/github/issues/liamtoaldo/Metropolitana.svg?style=flat-square
[issues-url]: https://github.com/liamtoaldo/Metropolitana/issues
[license-shield]: https://img.shields.io/github/license/liamtoaldo/Metropolitana.svg?style=flat-square
[license-url]: https://github.com/liamtoaldo/Metropolitana/blob/main/LICENSE
