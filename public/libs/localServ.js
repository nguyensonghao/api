'use strict';

angular.module('app')

.factory('localstoreServ', [
    function() {

        var service = {};
        var listChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var listEncryption = [{number:"46",text:"A"},{number:"31",text:"B"},{number:"93",text:"C"},{number:"24",text:"D"},{number:"37",text:"E"},{number:"84",text:"F"},{number:"41",text:"G"},{number:"71",text:"H"},{number:"16",text:"I"},{number:"49",text:"J"},{number:"52",text:"K"},{number:"59",text:"L"},{number:"45",text:"M"},{number:"96",text:"N"},{number:"51",text:"O"},{number:"36",text:"P"},{number:"95",text:"Q"},{number:"78",text:"R"},{number:"74",text:"S"},{number:"19",text:"T"},{number:"54",text:"U"},{number:"42",text:"V"},{number:"68",text:"W"},{number:"91",text:"X"},{number:"62",text:"Y"},{number:"65",text:"Z"},{number:"38",text:"a"},{number:"83",text:"b"},{number:"88",text:"c"},{number:"21",text:"d"},{number:"77",text:"e"},{number:"44",text:"f"},{number:"66",text:"g"},{number:"32",text:"h"},{number:"56",text:"i"},{number:"92",text:"j"},{number:"48",text:"k"},{number:"34",text:"l"},{number:"23",text:"m"},{number:"57",text:"n"},{number:"69",text:"o"},{number:"27",text:"p"},{number:"28",text:"q"},{number:"17",text:"r"},{number:"81",text:"s"},{number:"14",text:"t"},{number:"67",text:"u"},{number:"79",text:"v"},{number:"82",text:"w"},{number:"26",text:"x"},{number:"86",text:"y"},{number:"63",text:"z"},{number:"18",text:"0"},{number:"89",text:"1"},{number:"87",text:"2"},{number:"22",text:"3"},{number:"35",text:"4"},{number:"58",text:"5"},{number:"13",text:"6"},{number:"99",text:"7"},{number:"64",text:"8"},{number:"55",text:"9"}];

        service.setItem = function (key, value) {
            localStorage.setItem(key, angular.toJson(value))
        };

        service.getItem = function (key) {
            var result = localStorage.getItem(key);
            return angular.fromJson(result);
        };

        service.deleteItem = function (key) {
            localStorage.removeItem(key);
        };

        service.clear = function () {
            localStorage.clear();
        };

        service.randomString = function (length) {
            var text = "";
            for( var i = 0; i < length; i++ )
                text += listChar.charAt(Math.floor(Math.random() * listChar.length));

            return text;
        }

        service.encodeToken = function (tokenId, randomString) {
            var key = '';
            var randomNumber = service.randomNumber(10);
            var stringOne = randomString.substr(0, randomNumber);
            var stringTwo = randomString.substr(-(randomString.length-randomNumber));
            var encodeString = stringOne + tokenId + stringTwo;
            var size = encodeString.length;
            for (var i = 0; i < size; i++) {
                var c  = encodeString[i];
                var number = service.renderNumberfromChar(c);
                key += number;
            }

            key += randomNumber;
            return key;
        }

        service.randomNumber = function (max) {
            return Math.floor((Math.random() * max) + 1);
        }

        service.renderNumberfromChar = function (char) {
            var size = listEncryption.length;
            for (var i = 0; i < size; i++) {
                if (listEncryption[i].text == char) {
                    return listEncryption[i].number;
                }
            }

            return '';
        }


        return service;

    }]);