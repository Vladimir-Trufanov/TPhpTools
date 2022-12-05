<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                            *** UnicodeArrays.php ***

// ****************************************************************************
// * TPhpTools                                Массив избранных знаков юникода *
// *                                                                          *
// * v1.0, 05.12.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  27.11.2022 *
// ****************************************************************************

function UnicodeMake()
{

   $aUniCues=[
   [0,'Знаки всякие-разные',signBW,[
      // ['20','Пробел'],
      ['058D','Правосторонний знак вечности'],
      ['058E','Левосторонний знак вечности'],
      ['2020','Крестик'],
      ['2021','Двойной крестик'],
      ['203B','Знак сноски'],
      ['2042','Три звездочки в виде треугольника'],
      ['2055','Цветок знак препинания'],
      ['2056','Три точки пунктуации'],
      ['2058','Четыре точки пунктуации'],
      ['2059','Пять точек пунктуации'],
      ['205C','Точечный крест']]
   ],
   [1,'Символы валют',signBW,[
      ['0024','Доллар'],
      ['058F','Армянский драм'],
      ['20A0','Знак ЭКЮ'],
      ['20A1','Сальвадорский колон'],
      ['20A2','Бразильский крузейро'],
      ['20A3','Французский франк'],
      ['20A4','Лира'],
      ['20A5','Милль'],
      ['20A6','Нигерийская найра'],
      ['20A7','Испанская песета'],
      ['20A8','Рупия'],
      ['20A9','Вулонг'],
      ['20AA','Знак нового шекеля'],
      ['20AB','Вьетнамский донг'],
      ['20AC','Евро'],
      ['20AD','Лаоский кип'],
      ['20AE','Монгольский тугрик'],
      ['20AF','Греческая драхма'],
      ['20B0','Немецкий пфенниг'],
      ['20B1','Песо'],
      ['20B2','Парагвайский гуарани'],
      ['20B3','Аргентинский аустраль'],
      ['20B4','Гривна'],
      ['20B5','Ганский седи'],
      ['20B6','Турский ливр (турнуа)'],
      ['20B7','Знак спесмило'],
      ['20B8','Казахстанский тенге'],
      ['20B9','Индийская рупия'],
      ['20BA','Турецкая лира'],
      ['20BB','Нордическая марка'],
      ['20BC','Азербайджанский манат'],
      ['20BD','Рубль'],
      ['050E','Труфанка'], // Кириллическая строчная буква коми sje (в курсиве)
      ['20BE','Грузинский лари'],
      ['20BF','Биткойн']]
   ],
   [2,'Ожидаемые символы',signColor,[
      ['2600','-Доллар'],
      ['2601','-Армянский драм'],
      ['2602','-Знак ЭКЮ'],
      ['2603','-Сальвадорский колон'],
      ['2604','Бразильский крузейро'],
      ['2605','Французский франк'],
      ['2606','Лира'],
      ['2607','Милль'],
      ['2608','Нигерийская найра'],
      ['2609','Испанская песета'],
      ['260A','Рупия'],
      ['260C','Вулонг'],
      ['260D','Знак нового шекеля'],
      ['260E','Вьетнамский донг'],
      ['260F','Евро'],
      ['2639','Лаоский кип'],
      ['263A','Монгольский тугрик'],
      ['263B','Греческая драхма'],
      ['263C','Немецкий пфенниг'],
      ['263D','Песо'],
      ['263E','Парагвайский гуарани'],
      ['263D','Аргентинский аустраль'],
      ['263F','Гривна'],
      ['20B5','Ганский седи'],
      ['20B6','Турский ливр (турнуа)'],
      ['20B7','Знак спесмило'],
      ['20B8','Казахстанский тенге'],
      ['20B9','Индийская рупия'],
      ['20BA','Турецкая лира'],
      ['20BB','Нордическая марка'],
      ['20BC','Азербайджанский манат'],
      ['20BD','Рубль'],
      ['050E','Труфанка'],
      ['20BE','Грузинский лари'],
      ['20BF','Биткойн']]
   ],
   [3,'Еще',signBW,[
      ['1F600','Доллар'],
      ['1F601','Армянский драм'],
      ['1F602','Знак ЭКЮ'],
      ['1F603','Сальвадорский колон'],
      ['1F604','Бразильский крузейро'],
      ['1F605','Французский франк'],
      ['1F606','Лира'],
      ['1F607','Милль'],
      ['1F608','Нигерийская найра'],
      ['1F609','Испанская песета'],
      ['1F60A','Рупия'],
      ['1F60C','Вулонг'],
      ['1F60D','Знак нового шекеля'],
      ['1F60E','Вьетнамский донг'],
      ['1F60F','Евро'],
      ['20AD','Лаоский кип'],
      ['20AE','Монгольский тугрик'],
      ['20AF','Греческая драхма'],
      ['20B0','Немецкий пфенниг'],
      ['20B1','Песо'],
      ['20B2','Парагвайский гуарани'],
      ['20B3','Аргентинский аустраль'],
      ['20B4','Гривна'],
      ['20B5','Ганский седи'],
      ['20B6','Турский ливр (турнуа)'],
      ['20B7','Знак спесмило'],
      ['20B8','Казахстанский тенге'],
      ['20B9','Индийская рупия'],
      ['20BA','Турецкая лира'],
      ['20BB','Нордическая марка'],
      ['20BC','Азербайджанский манат'],
      ['20BD','Рубль'],
      ['050E','Труфанка'],
      ['20BE','Грузинский лари'],
      ['20BF','Биткойн']]
   ]
   ];
   return $aUniCues; 
}
// ****************************************************************************
// *    Выбрать фонт и определить стили таблиц для представления юникодов     *
// ****************************************************************************
function UnicodeStyle($FontFamily)
{
   // Настраиваем стили таблиц для представления юникодов
   echo '<style>';
   echo'  
      #setTable {border-collapse:separate; border-spacing:4px; width:100%}
      .setThead {text-align:left; font-size:1.8rem;}
      .setTbody tr td {width:4rem; height:4rem; font-size:3.6rem; text-align:center;}
      .setTbody tr td:hover {background:#a2c3dd; transition-duration:0.2s; border-radius:1rem;}
   ';
   // Определяем семейство шрифта: src:url(Styles/Lobster.ttf); 
   if ($FontFamily<>'') echo '.setThead {font-family:Emojitveme;}';
   echo '</style>';
}
// ****************************************************** UnicodeArrays.php ***

