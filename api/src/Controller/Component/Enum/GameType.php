<?php
namespace App\Controller\Component\Enum;

enum GameType: int {
	case Dnd = 0;
	case CallOfCthulu = 1;
	case EclipsePhase = 2;

	public static function getValue(int $gameType): GameType {
		if (GameType::Dnd->value == $gameType) {
			return GameType::Dnd;
		}
		if (GameType::CallOfCthulu->value == $gameType) {
			return GameType::CallOfCthulu;
		}
		if (GameType::EclipsePhase->value == $gameType) {
			return GameType::EclipsePhase;
		}
	}
	public static function getName(int $gameType): string {
		if (GameType::Dnd->value == $gameType) {
			return "Dungeons and Dragons";
		}
		if (GameType::CallOfCthulu->value == $gameType) {
			return "Call of Cthulu";
		}
		if (GameType::EclipsePhase->value == $gameType) {
			return "Eclipse Phase";
		}
	}
};
?>