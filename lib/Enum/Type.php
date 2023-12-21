<?php

namespace YgoProDeckClient\Enum;

enum Type: string
{
    // Main deck
    case Effect = "Effect Monster";
    case Flip = "Flip Effect Monster";
    case FlipTuner = "Flip Tuner Effect Monster";
    case Gemini = "Gemini Monster";
    case Normal = "Normal Monster";
    case NormalTuner = "Normal Tuner Monster";
    case PendulumEffect = "Pendulum Effect Monster";
    case PendulumRitual = "Pendulum Effect Ritual Monster";
    case PendulumFlip = "Pendulum Flip Effect Monster";
    case PendulumNormal = "Pendulum Normal Monster";
    case PendulumTunerEffect = "Pendulum Tuner Effect Monster";
    case RitualEffect = "Ritual Effect Monster";
    case Ritual = "Ritual Monster";
    case Spell = "Spell Card";
    case Spirit = "Spirit Monster";
    case Toon = "Toon Monster";
    case Trap = "Trap Card";
    case Tuner = "Tuner Monster";
    case Union = "Union Effect Monster";

    // Extra decks
    case Fusion = "Fusion Monster";
    case Link = "Link Monster";
    case PendulumFusion = "Pendulum Effect Fusion Monster";
    case Synchro = "Synchro Monster";
    case SynchroPendulum = "Synchro Pendulum Effect Monster";
    case SynchroTuner = "Synchro Tuner Monster";
    case Xyz = "XYZ Monster";
    case XyzPendulum = "XYZ Pendulum Effect Monster";

    // Other
    case Skill = "Skill Card";
    case Token = "Token";
}