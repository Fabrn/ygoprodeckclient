<?php

namespace YgoProDeckClient\Enum;

enum Race: string
{
    case Aqua = "Aqua";
    case Beast = "Beast";
    case BeastWarrior = "Beast-Warrior";
    case CreatorGod = "Creator-God";
    case Cyberse = "Cyberse";
    case Dinosaur = "Dinosaur";
    case DivineBeast = "Divine-Beast";
    case Dragon = "Dragon";
    case Fairy = "Fairy";
    case Fiend = "Fiend";
    case Fish = "Fish";
    /* CANNOT BE USED FOR FILTERING */ case Illusion = "Illusion";
    case Insect = "Insect";
    case Machine = "Machine";
    case Plant = "Plant";
    case Psychic = "Psychic";
    case Pyro = "Pyro";
    case Reptile = "Reptile";
    case Rock = "Rock";
    case SeaSerpent = "Sea Serpent";
    case Spellcaster = "Spellcaster";
    case Thunder = "Thunder";
    case Warrior = "Warrior";
    case WingedBeast = "Winged Beast";
    case Wyrm = "Wyrm";
    case Zombie = "Zombie";
}