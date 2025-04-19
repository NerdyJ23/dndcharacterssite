using DndCharacterSheets.Common.Domain.Entities.Character;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class CharacterClassConfiguration : IEntityTypeConfiguration<CharacterClass>
{
	public void Configure(EntityTypeBuilder<CharacterClass> builder)
	{
		builder.ToTable("Characters_Class");
		builder.HasKey(x => x.Id);
		builder.HasIndex(x => new { x.CharacterId, x.Name }).IsUnique();

		builder.Property(x => x.Id).HasConversion(IdConversions.CharacterClassIdConverter()).ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int").HasColumnName("ID");
		builder.Property(x => x.CharacterId).HasConversion(IdConversions.CharacterSheetIdConverter()).HasColumnName("Char_ID");
		builder.Property(x => x.Name).HasColumnName("Class").HasMaxLength(255).ForMySQLHasCollation("latin1_swedish_ci");
	}
}