

## SVX file adapter to PHP objects.

This project was created in order to promote and simplify the work with cave measurement data and make it possible to work with them in the Apache / Nginix environment.

What is **.svx** file format?

It is the file format of [Survex](https://survex.com/), an open source software for mapping and digital representation of cave plans and maps by [Olly Betts](https://github.com/ojwb).

![enter image description here](https://survex.com/img/aven-1.2.19-microsoft-windows.png)

Very, very simple example of an svx file:


    *begin Siedlecka_Cave
    
    *infer exports on
    
    *title "Siedlecka Cave"
    
    *units tape metres
    
    *calibrate declination 2.8
    
    *entrance 0
    
    *team "Waldemar Graban" compass clino - lider
    *team "Paulina Kulpa"   notes
    *team "Józef Kucia"     tape	      - dog
    *date 2019.05.18
    
    *data normal from to tape compass clino
    
    0	1	6.5	293	-36
    1	2	5.4	234	-41
    2	3	2.5	237	-38
    3	4	7.9	246	-33
    
    *end

This project allows you to convert cave measurement data from SVX to a PHP collection.

The method and example of use are in the file **index.php**

    use Waldekgraban\SvxAdapter\SvxImporter\Svx;
    use Waldekgraban\SvxAdapter\Adapter\Parser\Parser;
  
    $filename =  __DIR__  .  '/SvxExampleFiles/black_howk_down.svx';
    
    $importer =  new  Svx;
    $importer->importSvx($filename);
    
    $parser =  Parser::make($importer->file);
    
    $surveys = $parser->parse();
    $survey = $surveys->first();
    
    $data = $survey->getData()->first();
    
    foreach ($data->getMeasurements() as $measurement) {
        dump($measurement->getValues());
    }

An example of a converted advanced svx file:

![obraz](https://user-images.githubusercontent.com/18677004/173449179-4b961a52-a747-4153-8c9f-d5534f635762.png)

## 

**Huge thanks to**:

[Krzysztof Grabania](https://github.com/Dartui) - Great programming support

[Mateusz Golicz](https://github.com/mteg) - Cartographic consultations

[Olly Betts](https://github.com/ojwb) - Substantive support (Survex data structure)

[Dariusz Lubomski](https://github.com/dlubom) - Math consultations and technical resources

[Józef Kucia](https://github.com/jozefkucia) ![obraz](https://user-images.githubusercontent.com/18677004/173452533-43bd64cb-49c3-4352-9100-e5d90fa26cb0.png) - Consulting in the field of processing and structures of geological data
