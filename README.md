# Тестовое задание для php разработчика
## ФИО
Бородько Евгений Юрьевич

## Потраченное время
* 5 часов на составление и правки схемы
* 1 час на верстку
* 9 часов на написание кода, рефаторинг, дебагинг

## Описание архитектуры модуля
Вся логика и обработка полученных данных расположена в классе PageGenerator. Информация полученная из json файлов обрабатывается и помещается в классы. В основе иерархии классов лежит класс Game, который содержит вложеные подклассы. При помощи заполненного объекта Game и шаблона game получаем данные путем возврата содержимого из буфера вывода. После этого создается новый файл, в который записываются все полученные данные.

## Пояснение принятых решений
Было принято решение использовать структуру похожую на MVC, где вся логика обработки информации происходит в одном месте, а хранение и запись в другом.

## Инструкция по интеграции и использованию модуля
Сохранить модуль. Поместить файлы в папку source/matches. Запустить index.php.