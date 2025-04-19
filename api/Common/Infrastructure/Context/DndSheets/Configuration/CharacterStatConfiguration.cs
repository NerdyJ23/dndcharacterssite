using DndCharacterSheets.Common.Domain.Entities.Character;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class CharacterStatConfiguration : IEntityTypeConfiguration<CharacterStat>
{
	public void Configure(EntityTypeBuilder<CharacterStat> builder)
	{
		builder.ToTable("Characters_Stats");
		builder.HasKey(x => x.Id);
		builder.HasIndex(x => new { x.CharacterId, x.Name }).IsUnique();

		builder.Property(x => x.Id).HasConversion(IdConversions.CharacterStatIdConverter()).ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int").HasColumnName("ID");
		builder.Property(x => x.CharacterId).HasConversion(IdConversions.CharacterSheetIdConverter()).HasColumnName("Char_ID");
		builder.Property(x => x.Name).HasMaxLength(50).ForMySQLHasCollation("utf8mb4_0900_ai_ci");
	}
}