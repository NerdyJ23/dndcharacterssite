using Microsoft.EntityFrameworkCore;

namespace DndCharacterSheets.Common.Infrastructure.Context.DndSheets;

public sealed class DndSheetContext : DbContext
{
	public DndSheetContext()
	{
	}

	public DndSheetContext(DbContextOptions options) : base(options)
	{
	}

	protected override void OnModelCreating(ModelBuilder modelBuilder)
	{
		modelBuilder.ApplyConfigurationsFromAssembly(typeof(DndSheetContext).Assembly, x => x.AssemblyQualifiedName!.Contains("DndCharacterSheets.Common.Infrastructure.Context.DndSheets.Configuration"));
		base.OnModelCreating(modelBuilder);
	}
}