namespace DndCharacterSheets.Common.Domain.Entities.Character;

public sealed class CharacterClass
{
	public CharacterClassId Id { get; set; }
	public CharacterSheetId CharacterId { get; set; }
	public CharacterSheet? Character { get; set; }

	public required string Name { get; set; }
	public int Level { get; set; }
}

public readonly record struct CharacterClassId
{
	public int Value { get; init; }
	public CharacterClassId(int value) { Value = value; }
	public CharacterClassId() { }
}