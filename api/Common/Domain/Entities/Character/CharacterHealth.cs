namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed class CharacterHealth
{
	public CharacterHealthId Id { get; set; }
	public CharacterSheetId CharacterId { get; set; }
	public CharacterSheet? Character { get; set; }

	public int CurrentHp { get; set; }
	public int MaxHp { get; set; }
	public int TempHp { get; set; }
	public string HitDie { get; set; } = "1d6";

	public int DeathFails { get; set; }
	public int DeathSuccess { get; set; }
}

public readonly record struct CharacterHealthId
{
	public int Value { get; init; }
	public CharacterHealthId(int value) { Value = value; }
	public CharacterHealthId() { }
}