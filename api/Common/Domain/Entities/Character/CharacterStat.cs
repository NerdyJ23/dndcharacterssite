namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed class CharacterStat
{
	public CharacterStatId Id { get; set; }
	public CharacterSheetId CharacterId { get; set; }
	public CharacterSheet? Character { get; set; }

	public required string Name { get; set; }
	public int Value { get; set; }
}

public readonly record struct CharacterStatId
{
	public int Value { get; init; }
	public CharacterStatId(int value) { Value = value; }
	public CharacterStatId() { }
}