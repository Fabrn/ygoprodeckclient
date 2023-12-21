<?php

namespace YgoProDeckClient\Enum;

enum FrameType: string
{
    case Normal = "normal";
    case Effect = "effect";
    case Ritual = "ritual";
    case Fusion = "fusion";
    case Synchro = "synchro";
    case Xyz = "xyz";
    case Link = "link";
    case NormalPendulum = "normal_pendulum";
    case EffectPendulum = "effect_pendulum";
    case RitualPendulum = "ritual_pendulum";
    case FusionPendulum = "fusion_pendulum";
    case SynchroPendulum = "synchro_pendulum";
    case XyzPendulum = "xyz_pendulum";
    case Spell = "spell";
    case Trap = "trap";
    case Token = "token";
    case Skill = "skill";
}