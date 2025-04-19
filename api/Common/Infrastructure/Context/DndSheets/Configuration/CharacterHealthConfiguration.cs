using DndCharacterSheets.Common.Domain.Entities.Character;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using MySql.EntityFrameworkCore.Extensions;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration;

internal sealed class CharacterHealthConfiguration : IEntityTypeConfiguration<CharacterHealth>
{
	public void Configure(EntityTypeBuilder<CharacterHealth> builder)
	{
		builder.ToTable("Characters_Health");
		builder.HasKey(x => x.Id);

		builder.Property(x => x.Id).HasConversion(IdConversions.CharacterHealthIdConverter()).ValueGeneratedOnAdd().UseMySQLAutoIncrementColumn("int").HasColumnName("ID");
		builder.Property(x => x.CharacterId).HasConversion(IdConversions.CharacterSheetIdConverter());

		builder.Property(x => x.CurrentHp).HasColumnName("Current_Health").HasDefaultValue(0);
		builder.Property(x => x.MaxHp).HasColumnName("Max_Health").HasDefaultValue(0);
		builder.Property(x => x.TempHp).HasColumnName("Temporary_Health").HasDefaultValue(0);
		builder.Property(x => x.HitDie).HasColumnName("Hit_Die").HasMaxLength(10).ForMySQLHasCollation("utf8mb4_0900_ai_ci");
		builder.Property(x => x.DeathFails).HasColumnName("Death_Fails").HasDefaultValue(0);
		builder.Property(x => x.DeathSuccess).HasColumnName("Death_Success").HasDefaultValue(0);
	}
}